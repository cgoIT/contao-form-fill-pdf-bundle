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
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['fillPdf'] = 'fillPdfTemplate,filledPdfFolder,filledPdfNameTemplate,filledPdfDoNotOverwrite,fillPdfInsertTagPrefix,fillPdfInsertTagSuffix';

// Fields
$GLOBALS['TL_DCA']['tl_form']['fields'] = array_merge(
    ['fillPdf' => [
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 m12', 'submitOnChange' => true],
        'sql' => "char(1) NOT NULL default ''",
    ]],
    ['fillPdfTemplate' => [
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'extensions' => 'pdf', 'filesOnly' => true, 'tl_class' => 'clr w50'],
        'sql' => 'binary(16) NULL',
    ]],
    ['filledPdfFolder' => [
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => ['mandatory' => true, 'fieldType' => 'radio', 'files' => false, 'tl_class' => 'clr w50'],
        'sql' => 'binary(16) NULL',
    ]],
    ['filledPdfNameTemplate' => [
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr w50'],
        'sql' => "varchar(255) NOT NULL default ''",
    ]],
    ['filledPdfDoNotOverwrite' => [
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 m12'],
        'sql' => "char(1) NOT NULL default ''",
    ]],
    ['fillPdfInsertTagPrefix' => [
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'maxlength' => 5, 'tl_class' => 'w50'],
        'sql' => "varchar(5) NOT NULL default '[['",
    ]],
    ['fillPdfInsertTagSuffix' => [
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['mandatory' => true, 'maxlength' => 5, 'tl_class' => 'w50'],
        'sql' => "varchar(5) NOT NULL default ']]'",
    ]],
    $GLOBALS['TL_DCA']['tl_form']['fields']
);
