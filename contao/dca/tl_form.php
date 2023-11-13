<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2023, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Palettes
PaletteManipulator::create()
    ->addLegend('fp_legend', 'store_legend')
    ->addField('fpFill', 'fp_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_form')
;

$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'fpFill';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['fpFill'] = 'fpConfigs';

// Fields
$GLOBALS['TL_DCA']['tl_form']['fields'] = array_merge(
    ['fpFill' => [
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 m12', 'submitOnChange' => true],
        'sql' => "char(1) NOT NULL default ''",
    ]],
    ['fpLeadStore' => [
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['mandatory' => false, 'tl_class' => 'w50'],
    ]],
    ['fpConfigs' => [
        'exclude' => true,
        'inputType' => 'group',
        'min' => 1,
        'order' => false,
        'palette' => ['fpName', 'fpTemplate', 'fpTargetFolder', 'fpNameTemplate', 'fpDoNotOverwrite', 'fpInsertTagPrefix', 'fpInsertTagSuffix', 'fpFlatten'],
        'fields' => [
            'fpName' => [
                'exclude' => true,
                'inputType' => 'text',
                'eval' => ['mandatory' => true, 'maxlength' => 50, 'doNotCopy' => true, 'tl_class' => 'w50'],
            ],
            'fpTemplate' => [
                'exclude' => true,
                'inputType' => 'fileTree',
                'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'extensions' => 'pdf', 'filesOnly' => true, 'doNotCopy' => true, 'tl_class' => 'clr w50'],
            ],
            'fpTargetFolder' => [
                'exclude' => true,
                'inputType' => 'fileTree',
                'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'files' => false, 'doNotCopy' => true, 'tl_class' => 'clr w50'],
            ],
            'fpNameTemplate' => [
                'exclude' => true,
                'inputType' => 'text',
                'eval' => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr w50'],
            ],
            'fpDoNotOverwrite' => [
                'exclude' => true,
                'inputType' => 'checkbox',
                'eval' => ['tl_class' => 'w50 m12'],
            ],
            'fpInsertTagPrefix' => [
                'exclude' => true,
                'inputType' => 'text',
                'eval' => ['mandatory' => false, 'maxlength' => 5, 'tl_class' => 'w50'],
            ],
            'fpInsertTagSuffix' => [
                'exclude' => true,
                'inputType' => 'text',
                'eval' => ['mandatory' => false, 'maxlength' => 5, 'tl_class' => 'w50'],
            ],
            'fpFlatten' => [
                'exclude' => true,
                'inputType' => 'checkbox',
                'eval' => ['mandatory' => false, 'tl_class' => 'w50'],
            ],
        ],
        'sql' => 'blob NULL',
    ]],
    $GLOBALS['TL_DCA']['tl_form']['fields'],
);
