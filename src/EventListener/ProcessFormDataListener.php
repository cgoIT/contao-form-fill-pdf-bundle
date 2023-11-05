<?php

declare(strict_types=1);

namespace Cgoit\FormFillPdfBundle\EventListener;

use Codefog\HasteBundle\StringParser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\Date;
use Contao\FilesModel;
use Contao\Folder;
use Contao\Form;
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use mikehaertl\pdftk\Pdf;

class ProcessFormDataListener
{
    public function __construct(private readonly string $projectDir, private readonly Connection $db, private readonly StringParser $stringParser, private readonly InsertTagParser $insertTagParser)
    {
    }

    /**
     * @param array<mixed>      $submittedData
     * @param array<mixed>      $formData
     * @param array<mixed>|null $files
     * @param array<mixed>      $labels
     */
    #[AsHook('processFormData', priority: 100)]
    public function fillTemplate(array $submittedData, array $formData, array|null $files, array $labels, Form $form): void
    {
        if ($form->fillPdf) { // @phpstan-ignore-line
            if (
                !empty($objPdfTemplate = FilesModel::findByUuid($form->fillPdfTemplate)) && // @phpstan-ignore-line
                !empty($objTargetFolder = FilesModel::findByUuid($form->filledPdfFolder)) // @phpstan-ignore-line
            ) {
                $this->processInsertTags($submittedData, $formData['fillPdfInsertTagPrefix'] ?? '[[', $formData['fillPdfInsertTagSuffix'] ?? ']]');

                $tokens = $this->generateTokens(
                    $submittedData,
                    $formData,
                    $files,
                    $labels
                )
                ;

                $fileName = $this->getFileName($tokens, $formData, $objTargetFolder->path);

                $pdf = new Pdf($objPdfTemplate->getAbsolutePath());
                $result = $pdf->fillForm($tokens)
                    ->needAppearances()
                    ->flatten()
                    ->saveAs($objTargetFolder->getAbsolutePath().'/'.$fileName)
                ;

                // Always check for errors
                if (false === $result) {
                    System::getContainer()
                        ->get('monolog.logger.contao.general')
                        ->error($pdf->getError())
                    ;
                }
            }
        }
    }

    /**
     * @param array<mixed> $tokens
     * @param array<mixed> $formData
     */
    private function getFileName(array $tokens, array $formData, string $targetFolder): string
    {
        $strExtension = 'pdf';

        $strTemplate = !empty($formData['filledPdfNameTemplate']) ? StringUtil::decodeEntities($formData['filledPdfNameTemplate']) : $formData['id'].'_'.Date::parse('Y-m-d');

        $strFileName = $this->insertTagParser->replace($strTemplate);
        $strFileName = str_replace(array_keys($tokens), array_values($tokens), $strFileName);
        $strFileName = StringUtil::sanitizeFileName($strFileName);

        // Do not overwrite existing files
        if (!empty($formData['filledPdfDoNotOverwrite']) && file_exists($this->projectDir.'/'.$targetFolder.'/'.$strFileName.'.'.$strExtension)) {
            $offset = 1;

            $arrAll = Folder::scan($this->projectDir.'/'.$targetFolder, true);
            $arrFiles = preg_grep('/^'.preg_quote($strFileName, '/').'.*\.'.preg_quote($strExtension, '/').'/', $arrAll);

            foreach ($arrFiles as $strFile) {
                if (preg_match('/__[0-9]+\.'.preg_quote($strExtension, '/').'$/', $strFile)) {
                    $strFile = str_replace('.'.$strExtension, '', $strFile);
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
        $arrTokens['##raw_data##'] = '';
        $arrTokens['##raw_data_filled##'] = '';

        foreach ($arrData as $k => $v) {
            $this->stringParser->flatten($v, '##form_'.$k.'##', $arrTokens, $delimiter);
            $arrTokens['##formlabel_'.$k.'##'] = $arrLabels[$k] ?? ucfirst($k);
            $arrTokens['##raw_data##'] .= ($arrLabels[$k] ?? ucfirst($k)).': '.(\is_array($v) ? implode(', ', $v) : $v)."\n";

            if (\is_array($v) || \strlen($v)) {
                $arrTokens['##raw_data_filled##'] .= ($arrLabels[$k] ?? ucfirst($k)).': '.(\is_array($v) ? implode(', ', $v) : $v)."\n";
            }
        }

        foreach ($arrForm as $k => $v) {
            $this->stringParser->flatten($v, '##formconfig_'.$k.'##', $arrTokens, $delimiter);
        }

        if (class_exists(Terminal42LeadsBundle::class) && $arrForm['leadEnabled']) {
            $arrLeadData = $this->getLeadData($arrForm, $arrData);

            foreach ($arrLeadData as $k => $v) {
                $this->stringParser->flatten($v, '##lead_'.$k.'##', $arrTokens, $delimiter);
            }
        }

        // Administrator e-mail
        $arrTokens['##admin_email##'] = $GLOBALS['TL_ADMIN_EMAIL'];

        // Upload fields
        $arrFileNames = [];

        if (!empty($arrFiles)) {
            foreach ($arrFiles as $fieldName => $file) {
                $arrTokens['##form_'.$fieldName.'##'] = $file['tmp_name'];
                $arrFileNames[] = $file['name'];
            }
        }
        $arrTokens['##filenames##'] = implode($delimiter, $arrFileNames);

        return $arrTokens;
    }

    /**
     * @param array<mixed> $formConfig
     * @param array<mixed> $postData
     *
     * @throws Exception
     *
     * @return array<mixed>
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
     * @throws Exception
     *
     * @return array<mixed>
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
                [$formId, $mainId]
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
            [$formId]
        );
    }

    /**
     * @param array<mixed> $submittedData
     */
    private function processInsertTags(array &$submittedData, string $strPrefix, string $strSuffix): void
    {
        foreach ($submittedData as $key => $value) {
            if (str_starts_with($value, $strPrefix) && str_ends_with($value, $strSuffix)) {
                $insertTag = str_replace([$strPrefix, $strSuffix], ['{{', '}}'], $value);
                $submittedData[$key] = $this->insertTagParser->replace($insertTag);
            }
        }
    }
}
