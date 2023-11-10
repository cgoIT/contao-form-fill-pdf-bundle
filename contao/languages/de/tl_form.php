<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2023, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

$GLOBALS['TL_LANG']['tl_form']['fp_legend'] = 'PDF-Vorlage';

$GLOBALS['TL_LANG']['tl_form']['fpFill']['0'] = 'PDF-Vorlage befüllen';
$GLOBALS['TL_LANG']['tl_form']['fpFill']['1'] = 'Mit den eingegebenen Werten im Formular soll ein PDF-Formular befüllt werden.';

$GLOBALS['TL_LANG']['tl_form']['fpConfigs']['0'] = 'Konfiguration';
$GLOBALS['TL_LANG']['tl_form']['fpConfigs']['1'] = 'Eine oder mehrere Konfiguration zum Befüllen einer PDF-Vorlage.';

$GLOBALS['TL_LANG']['tl_form']['fpName']['0'] = 'Bezeichnung';
$GLOBALS['TL_LANG']['tl_form']['fpName']['1'] = 'Diese Bezeichnung wird u.a. dafür genutzt das erzeugte PDF als SimpleToken nutzbar zu machen. Eine Bezeichnung <code>merged</code> führt zu einem SimpleToken <code>##file_merged##</code>.';

$GLOBALS['TL_LANG']['tl_form']['fpTemplate']['0'] = 'PDF-Vorlage';
$GLOBALS['TL_LANG']['tl_form']['fpTemplate']['1'] = 'Die Vorlage, die befüllt werden soll.';

$GLOBALS['TL_LANG']['tl_form']['fpTargetFolder']['0'] = 'Speicherort für befüllte PDF-Vorlagen';
$GLOBALS['TL_LANG']['tl_form']['fpTargetFolder']['1'] = 'Pfad, unter dem die befüllten PDF-Dateien abgelegt werden sollen.';

$GLOBALS['TL_LANG']['tl_form']['fpNameTemplate']['0'] = 'Template für Dateinamen des befüllten PDFs';
$GLOBALS['TL_LANG']['tl_form']['fpNameTemplate']['1'] = 'Das befüllte PDF wird mit diesem Dateinamen im Ziel-Ordner angelegt. Als Platzhalter können Insert-Tags und SimpleTokens (##form_*##, ##formconfig_*##) verwendet werden.';

$GLOBALS['TL_LANG']['tl_form']['fpDoNotOverwrite']['0'] = 'Bestehende Dateien erhalten';
$GLOBALS['TL_LANG']['tl_form']['fpDoNotOverwrite']['1'] = 'Der neuen Datei ein numerisches Suffix hinzufügen, wenn der Dateiname bereits existiert.';

$GLOBALS['TL_LANG']['tl_form']['fpInsertTagPrefix']['0'] = 'Insert-Tag Prefix';
$GLOBALS['TL_LANG']['tl_form']['fpInsertTagPrefix']['1'] = 'Prefix für Werte, die serverseitig als Insert-Tags aufgelöst werden sollen. Default=[[';

$GLOBALS['TL_LANG']['tl_form']['fpInsertTagSuffix']['0'] = 'Insert-Tag Suffix';
$GLOBALS['TL_LANG']['tl_form']['fpInsertTagSuffix']['1'] = 'Suffix für Werte, die serverseitig als Insert-Tags aufgelöst werden sollen. Default=]]';

$GLOBALS['TL_LANG']['tl_form']['fpFlatten']['0'] = 'Formularfelder entfernen';
$GLOBALS['TL_LANG']['tl_form']['fpFlatten']['1'] = 'Wenn aktiviert werden alle Formularfelder nach dem Befüllen aus dem PDF entfernt.';

$GLOBALS['TL_LANG']['tl_form']['fpLeadStore']['0'] = 'In Anfrage speichern';
$GLOBALS['TL_LANG']['tl_form']['fpLeadStore']['1'] = 'Wählen Sie ob/wie dieses Feld gespeichert werden soll. Bei Formularverbindungen wählen Sie das entsprechende Feld im Hauptformular.';
