<?php

declare(strict_types=1);

namespace Cgoit\FormFillPdfBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FormModel;
use Contao\StringUtil;

#[AsCallback(table: 'tl_form_field', target: 'fields.fpConfigs.options')]
class FormFieldFpConfigsOptionsListener
{
    /**
     * @return array<mixed>
     */
    public function __invoke(DataContainer|null $dc): array
    {
        $arrConfigs = [];

        if (null !== $dc) {
            $pid = $dc->activeRecord->pid ?? 0;

            if ($pid && null !== $objForm = FormModel::findById($pid)) {
                $fpConfigs = StringUtil::deserialize($objForm->fpConfigs, true);

                foreach ($fpConfigs as $config) {
                    $arrConfigs[$config['fpName']] = $config['fpName'];
                }
            }
        }

        return $arrConfigs;
    }
}
