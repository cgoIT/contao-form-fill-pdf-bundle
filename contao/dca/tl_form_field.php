<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2023, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_form_field']['palettes']['fp_generate_pdf'] = '{type_legend},type;{fconfig_legend},fpConfigs;{invisible_legend:hide},invisible';

$GLOBALS['TL_DCA']['tl_form_field']['fields']['fpConfigs'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['mandatory' => true, 'multiple' => true, 'tl_class' => 'w100'],
    'sql' => 'blob NULL',
];
