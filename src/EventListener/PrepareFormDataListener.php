<?php

declare(strict_types=1);

namespace Cgoit\FormFillPdfBundle\EventListener;

use Codefog\HasteBundle\StringParser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\Date;
use Contao\Dbafs;
use Contao\File;
use Contao\FilesModel;
use Contao\Folder;
use Contao\Form;
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use mikehaertl\pdftk\Pdf;

#[AsHook('prepareFormData')]
class PrepareFormDataListener
{
    public function __construct(
        private readonly string $projectDir,
        private readonly Connection $db,
        private readonly StringParser $stringParser,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    /**
     * @param array<mixed> $submittedData
     * @param array<mixed> $labels
     * @param array<mixed> $arrFields
     */
    public function __invoke(array &$submittedData, array $labels, array $arrFields, Form $form): void
    {
        if ($form->fpFill) { // @phpstan-ignore-line
            $formData = $form->getModel()->row();
            $arrFiles = $_SESSION['FILES'] ?? [];
            $tokens = $this->generateTokens(
                $submittedData,
                $formData,
                $arrFiles,
                $labels,
            );

            $arrConfig = StringUtil::deserialize($formData['fpConfigs'], true);
            $leadStore = false;

            foreach ($arrConfig as $config) {
                $leadStore |= $this->fillPdf($config, $submittedData, $tokens);
            }

            if ($leadStore) {
                $submittedData['leads-fp-id'] = md5(uniqid((string) random_int(0, mt_getrandmax()), true));
            }
        }
    }

    /**
     * @param array<mixed> $config
     * @param array<mixed> $submittedData
     * @param array<mixed> $tokens
     */
    private function fillPdf(array $config, array $submittedData, array $tokens): bool
    {
        if (
            !empty($objPdfTemplate = FilesModel::findByUuid($config['fpTemplate']))
            && !empty($objTargetFolder = FilesModel::findByUuid($config['fpTargetFolder']))
        ) {
            $this->processInsertTags($submittedData, $config['fpInsertTagPrefix'] ?? '[[', $config['fpInsertTagSuffix'] ?? ']]');

            $fileName = $this->getFileName($tokens, $config, $objTargetFolder->path);

            $pdf = new Pdf($objPdfTemplate->getAbsolutePath());
            $result = $pdf
                ->fillForm($tokens)
                ->needAppearances()
            ;

            if (!empty($config['fpFlatten'])) {
                $result = $result->flatten();
            }

            $result = $result->saveAs($objTargetFolder->getAbsolutePath().'/'.$fileName);

            // Always check for errors
            if (false === $result) {
                System::getContainer()
                    ->get('monolog.logger.contao.general')
                    ->error('[Contao Form Fill PDF] Error during merge: '.$pdf->getError())
                ;
            } else {
                System::getContainer()
                    ->get('monolog.logger.contao.general')
                    ->info('[Contao Form Fill PDF] Merged PDF stored at '.$objTargetFolder->path.'/'.$fileName)
                ;

                // PDF is merged. Add it to the array in Session
                $objFile = new File($objTargetFolder->path.'/'.$fileName);
                $fileModel = Dbafs::addResource($objFile->path);
                $filledPdf = [
                    'name' => $fileName,
                    'uuid' => StringUtil::binToUuid($fileModel->uuid),
                    'full_path' => $objFile->path,
                    'type' => $objFile->mime,
                    'tmp_name' => $objFile->path,
                    'error' => 0,
                    'size' => $objFile->size,
                    'uploaded' => true,
                ];

                $_SESSION['FILES'][$config['fpName']] = $filledPdf;
            }

            return !empty($config['fpLeadStore']);
        }

        return false;
    }

    /**
     * @param array<mixed> $tokens
     * @param array<mixed> $formData
     */
    private function getFileName(array $tokens, array $formData, string $targetFolder): string
    {
        $strExtension = 'pdf';

        $strTemplate = !empty($formData['fpNameTemplate']) ? StringUtil::decodeEntities($formData['fpNameTemplate']) : $formData['id'].'_'.Date::parse('Y-m-d');

        $strFileName = $this->insertTagParser->replaceInline($strTemplate);
        $strFileName = str_replace(array_keys($tokens), array_values($tokens), $strFileName);
        $strFileName = StringUtil::sanitizeFileName($strFileName);

        // Do not overwrite existing files
        if (!empty($formData['fpDoNotOverwrite']) && file_exists($this->projectDir.'/'.$targetFolder.'/'.$strFileName.'.'.$strExtension)) {
            $offset = 1;

            $arrAll = Folder::scan($this->projectDir.'/'.$targetFolder, true);
            $arrFiles = preg_grep('/^'.preg_quote($strFileName, '/').'.*\.'.preg_quote($strExtension, '/').'/', $arrAll);

            foreach ($arrFiles as $strFile) {
                if (preg_match('/__[0-9]+\.'.preg_quote($strExtension, '/').'$/', (string) $strFile)) {
                    $strFile = str_replace('.'.$strExtension, '', (string) $strFile);
                    $intValue = (int) substr($strFile, strrpos($strFile, '_') + 1);

                    $offset = max($offset, $intValue);
                }
            }

            $strFileName = str_replace($strFileName, $strFileName.'__'.++$offset, $strFileName);
        }

        return $strFileName.'.'.$strExtension;
    }

    /**
     * Generate the tokens.
     *
     * @param array<mixed>      $arrData
     * @param array<mixed>      $arrForm
     * @param array<mixed>|null $arrFiles
     * @param array<mixed>      $arrLabels
     *
     * @return array<mixed>
     */
    private function generateTokens(array $arrData, array $arrForm, array|null $arrFiles, array $arrLabels, string $delimiter = ', '): array
    {
        $arrTokens = [];
        $arrTokens['raw_data'] = '';
        $arrTokens['raw_data_filled'] = '';

        foreach ($arrData as $k => $v) {
            $this->stringParser->flatten($v, 'form_'.$k, $arrTokens, $delimiter);
            $arrTokens['formlabel_'.$k] = $arrLabels[$k] ?? ucfirst($k);
            $arrTokens['raw_data'] .= ($arrLabels[$k] ?? ucfirst($k)).': '.(\is_array($v) ? implode(', ', $v) : $v)."\n";

            if (\is_array($v) || \strlen((string) $v)) {
                $arrTokens['raw_data_filled'] .= ($arrLabels[$k] ?? ucfirst($k)).': '.(\is_array($v) ? implode(', ', $v) : $v)."\n";
            }
        }

        foreach ($arrForm as $k => $v) {
            $this->stringParser->flatten($v, 'formconfig_'.$k, $arrTokens, $delimiter);
        }

        if (class_exists(Terminal42LeadsBundle::class) && $arrForm['leadEnabled']) {
            $arrLeadData = $this->getLeadData($arrForm, $arrData);

            foreach ($arrLeadData as $k => $v) {
                $this->stringParser->flatten($v, 'lead_'.$k, $arrTokens, $delimiter);
            }
        }

        // Administrator e-mail
        $arrTokens['admin_email'] = $GLOBALS['TL_ADMIN_EMAIL'];

        // Upload fields
        $arrFileNames = [];

        if (!empty($arrFiles)) {
            foreach ($arrFiles as $fieldName => $file) {
                $arrTokens['form_'.$fieldName] = $file['tmp_name'];
                $arrFileNames[] = $file['name'];
            }
        }
        $arrTokens['filenames'] = implode($delimiter, $arrFileNames);

        $return = [];

        foreach ($arrTokens as $key => $token) {
            $return['##'.$key.'##'] = $token;
        }

        return $return;
    }

    /**
     * @param array<mixed> $formConfig
     * @param array<mixed> $postData
     *
     * @return array<mixed>
     *
     * @throws Exception
     */
    private function getLeadData(array $formConfig, array $postData): array
    {
        $leadData = [];
        $fields = $this->getFormFields((int) $formConfig['id'], (int) $formConfig['leadMain']);

        foreach ($fields as $field) {
            if (\array_key_exists($field['name'], $postData) && $postData[$field['name']]) {
                $leadData[$field['name']] = $postData[$field['name']];
            }
        }

        return $leadData;
    }

    /**
     * @return array<mixed>
     *
     * @throws Exception
     */
    private function getFormFields(int $formId, int $mainId): array
    {
        if ($mainId > 0) {
            return $this->db->fetchAllAssociative(
                <<<'SQL'
                        SELECT
                            main_field.*,
                            form_field.id AS field_id,
                            form_field.name AS postName
                        FROM tl_form_field form_field
                            LEFT JOIN tl_form_field main_field ON form_field.leadStore=main_field.id
                        WHERE
                            form_field.pid=?
                          AND main_field.pid=?
                          AND form_field.leadStore>0
                          AND main_field.leadStore='1'
                          AND form_field.invisible=''
                        ORDER BY main_field.sorting;
                    SQL,
                [$formId, $mainId],
            );
        }

        return $this->db->fetchAllAssociative(
            <<<'SQL'
                    SELECT
                        *,
                        id AS field_id,
                        name AS postName
                    FROM tl_form_field
                    WHERE pid=?
                      AND leadStore='1'
                      AND invisible=''
                    ORDER BY sorting
                SQL,
            [$formId],
        );
    }

    /**
     * @param array<mixed> $submittedData
     */
    private function processInsertTags(array &$submittedData, string $strPrefix, string $strSuffix): void
    {
        foreach ($submittedData as $key => $value) {
            if (\is_string($value)) {
                $submittedData[$key] = $this->replaceInsertTag($value, $strPrefix, $strSuffix);
            } elseif (\is_array($value)) {
                $newValue = [];

                foreach ($value as $item) {
                    if (\is_string($item)) {
                        $newValue[] = $this->replaceInsertTag($item, $strPrefix, $strSuffix);
                    } else {
                        $newValue[] = $item;
                    }
                }

                $submittedData[$key] = $newValue;
            }
        }
    }

    private function replaceInsertTag(string $value, string $strPrefix, string $strSuffix): string
    {
        if (str_starts_with($value, $strPrefix) && str_ends_with($value, $strSuffix)) {
            $insertTag = str_replace([$strPrefix, $strSuffix], ['{{', '}}'], $value);

            return $this->insertTagParser->replaceInline($insertTag);
        }

        return $value;
    }
}
