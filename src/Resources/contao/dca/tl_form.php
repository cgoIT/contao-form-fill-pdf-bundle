<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle.
 *
 * (c) Carsten Götzinger
 *
 * @license LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Palettes
PaletteManipulator::create()
    ->addLegend('fillPdf_legend', 'store_legend')
    ->addField('fillPdf', 'fillPdf_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_form')
;

$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'fillPdf';
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['fillPdf'] = 'fillPdfTemplate,fillPdfSavePath';

// Fields
$GLOBALS['TL_DCA']['tl_content']['fields'] = array_merge(
    ['fillPdf' => [
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 m12', 'submitOnChange' => true],
        'sql' => "char(1) NOT NULL default ''",
    ]],
    ['fillPdfTemplate' => [
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'extensions' => 'pdf', 'tl_class' => 'clr w50'],
        'sql' => 'binary(16) NULL',
    ]],
    ['filledPdfFolder' => [
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'tl_class' => 'w50'],
        'sql' => 'binary(16) NULL',
    ]],
    $GLOBALS['TL_DCA']['tl_form']['fields']
);
