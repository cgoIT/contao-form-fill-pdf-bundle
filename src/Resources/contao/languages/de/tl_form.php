<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle.
 *
 * (c) Carsten Götzinger
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_LANG']['tl_form']['fillPdf_legend'] = 'PDF-Vorlage';

$GLOBALS['TL_LANG']['tl_form']['fillPdf']['0'] = 'PDF-Vorlage befüllen';
$GLOBALS['TL_LANG']['tl_form']['fillPdf']['1'] = 'Mit den eingegebenen Werten im Formular soll ein PDF-Formular befüllt werden.';

$GLOBALS['TL_LANG']['tl_form']['fillPdfTemplate']['0'] = 'PDF-Vorlage';
$GLOBALS['TL_LANG']['tl_form']['fillPdfTemplate']['1'] = 'Die Vorlage, die befüllt werden soll.';

$GLOBALS['TL_LANG']['tl_form']['filledPdfFolder']['0'] = 'Speicherort für befüllte PDF-Vorlagen';
$GLOBALS['TL_LANG']['tl_form']['filledPdfFolder']['1'] = 'Pfad, unter dem die befüllten PDF-Dateien abgelegt werden sollen.';

$GLOBALS['TL_LANG']['tl_form']['filledPdfNameTemplate']['0'] = 'Template für Dateinamen des befüllten PDFs';
$GLOBALS['TL_LANG']['tl_form']['filledPdfNameTemplate']['1'] = 'Das befüllte PDF wird mit diesem Dateinamen im Ziel-Ordner angelegt. Als Platzhalter können Insert-Tags und SimpleTokens (##form_*##) verwendet werden.';

$GLOBALS['TL_LANG']['tl_form']['filledPdfDoNotOverwrite']['0'] = 'Bestehende Dateien erhalten';
$GLOBALS['TL_LANG']['tl_form']['filledPdfDoNotOverwrite']['1'] = 'Der neuen Datei ein numerisches Suffix hinzufügen, wenn der Dateiname bereits existiert.';

$GLOBALS['TL_LANG']['tl_form']['fillPdfInsertTagPrefix']['0'] = 'Insert-Tag Prefix';
$GLOBALS['TL_LANG']['tl_form']['fillPdfInsertTagPrefix']['1'] = 'Prefix für Werte, die serverseitig als Insert-Tags aufgelöst werden sollen.';

$GLOBALS['TL_LANG']['tl_form']['fillPdfInsertTagSuffix']['0'] = 'Insert-Tag Suffix';
$GLOBALS['TL_LANG']['tl_form']['fillPdfInsertTagSuffix']['1'] = 'Suffix für Werte, die serverseitig als Insert-Tags aufgelöst werden sollen.';
