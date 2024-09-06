<?php

declare(strict_types=1);

namespace Cgoit\FormFillPdfBundle\EventListener;

use Cgoit\FormFillPdfBundle\Widget\GeneratePdf;
use Codefog\HasteBundle\StringParser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\Date;
use Contao\Dbafs;
use Contao\File;
use Contao\FilesModel;
use Contao\Folder;
use Contao\Form;
use Contao\FormFieldModel;
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use mikehaertl\pdftk\Pdf;
use Symfony\Component\Filesystem\Filesystem;
use Terminal42\MultipageFormsBundle\FormManager;
use Terminal42\MultipageFormsBundle\FormManagerFactoryInterface;

#[AsHook('prepareFormData', priority: 100)]
class PrepareFormDataListener
{
    private readonly Filesystem $fs;

    /**
     * @param FormManagerFactoryInterface|null $formManagerFactory
     */
    public function __construct(
        private readonly string $projectDir,
        private readonly Connection $db,
        private readonly StringParser $stringParser,
        private readonly InsertTagParser $insertTagParser,
        private $formManagerFactory, // @phpstan-ignore-line
    ) {
        $this->fs = new Filesystem();
    }

    /**
     * @param array<mixed> $submittedData
     * @param array<mixed> $labels
     * @param array<mixed> $arrFields
     * @param array<mixed> $arrFiles
     */
    public function __invoke(array &$submittedData, array $labels, array $arrFields, Form $form, array &$arrFiles): void
    {
        if ($form->fpFill) { // @phpstan-ignore-line
            $formData = $form->getModel()->row();
            $submitted = $submittedData;

            $arrConfiguredConfigs = null;

            if (null !== $this->formManagerFactory) {
                $manager = $this->formManagerFactory->forFormId((int) $form->id); // @phpstan-ignore-line

                if (
                    $manager->isValidFormFieldCombination()
                    && ((null !== $generatePdfWidget = $this->getGeneratePdfWidget($manager)) || $manager->isLastStep())
                ) {
                    $allData = $manager->getDataOfAllSteps();

                    // Replace data by reference and then return so the default Contao routine kicks in
                    $submitted = array_merge($allData->getAllSubmitted(), $submitted);
                    $labels = array_merge($allData->getAllLabels(), $labels);
                    $arrFiles = array_merge($allData->getAllFiles(), $arrFiles);

                    if (null !== $generatePdfWidget) {
                        $arrConfiguredConfigs = StringUtil::deserialize($generatePdfWidget->fpConfigs, true);
                    }
                } else {
                    return;
                }
            }

            $tokens = $this->generateTokens(
                $submitted,
                $formData,
                $arrFiles,
                $labels,
            );

            $arrConfig = StringUtil::deserialize($formData['fpConfigs'], true);
            $leadStore = false;

            if (null !== $arrConfiguredConfigs) {
                $arrEffectiveConfig = [];

                foreach ($arrConfig as $config) {
                    if (\in_array($config['fpName'], array_values($arrConfiguredConfigs), true)) {
                        $arrEffectiveConfig[] = $config;
                    }
                }
            } else {
                $arrEffectiveConfig = $arrConfig;
            }

            foreach ($arrEffectiveConfig as $config) {
                $leadStore |= $this->fillPdf($config, $submitted, $tokens, $arrFiles);
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
     * @param array<mixed> $files
     */
    private function fillPdf(array $config, array $submittedData, array $tokens, array &$files): bool
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
                    'full_path' => $this->projectDir.'/'.$objFile->path,
                    'type' => $objFile->mime,
                    'tmp_name' => $this->projectDir.'/'.$objFile->path,
                    'error' => 0,
                    'size' => $objFile->size,
                    'uploaded' => false,
                ];

                $files[$config['fpName']] = $filledPdf;

                // TODO check if needed. Where is the data removed from the session
                //                $_SESSION['FILES'][$config['fpName']][] = $filledPdf;
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
        $strFileName = str_replace(' ', '_', $strFileName);

        // Do not overwrite existing files
        if (!empty($formData['fpDoNotOverwrite']) && $this->fs->exists($this->projectDir.'/'.$targetFolder.'/'.$strFileName.'.'.$strExtension)) {
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
                if ($this->isAssocArray($file)) {
                    if (\array_key_exists('tmp_name', $file)) {
                        $arrTokens['form_'.$fieldName] = $file['tmp_name'];
                        $arrFileNames[] = $file['name'];
                    }
                } else {
                    foreach ($file as $upload) {
                        if (!\is_array($upload) && !\array_key_exists('tmp_name', (array) $upload)) {
                            throw new \InvalidArgumentException('$value must be an array normalized by the FileUploadNormalizer service.');
                        }

                        $arrTokens['form_'.$fieldName] = $upload['tmp_name'];
                        $arrFileNames[] = $upload['name'];
                    }
                }
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

    /**
     * @param FormManager $manager
     */
    private function getGeneratePdfWidget($manager): FormFieldModel|null // @phpstan-ignore-line
    {
        if (null !== $arrWidgets = $manager->getFieldsForStep($manager->getCurrentStep())) { // @phpstan-ignore-line
            foreach ($arrWidgets as $widget) {
                if (GeneratePdf::TYPE === $widget->type) {
                    return $widget;
                }
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    private function isAssocArray(mixed $arr)
    {
        if (!\is_array($arr)) {
            return false;
        }

        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, \count($arr) - 1);
    }
}
