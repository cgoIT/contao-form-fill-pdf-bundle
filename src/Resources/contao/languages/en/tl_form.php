<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle.
 *
 * (c) Carsten Götzinger
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_LANG']['tl_form']['fp_legend'] = 'PDF Template';

$GLOBALS['TL_LANG']['tl_form']['fpFill']['0'] = 'Merge PDF template';
$GLOBALS['TL_LANG']['tl_form']['fpFill']['1'] = 'A PDF form is to be merged with the values entered in the form.';

$GLOBALS['TL_LANG']['tl_form']['fpConfigs']['0'] = 'Configuration';
$GLOBALS['TL_LANG']['tl_form']['fpConfigs']['1'] = 'One or more configurations for merging a PDF template.';

$GLOBALS['TL_LANG']['tl_form']['fpName']['0'] = 'Name';
$GLOBALS['TL_LANG']['tl_form']['fpName']['1'] = 'This name is used, among other things, to make the generated PDF usable as a SimpleToken. A name <code>merged</code> leads to a SimpleToken <code>##file_merged##</code>.';

$GLOBALS['TL_LANG']['tl_form']['fpTemplate']['0'] = 'PDF template';
$GLOBALS['TL_LANG']['tl_form']['fpTemplate']['1'] = 'The template to be filled.';

$GLOBALS['TL_LANG']['tl_form']['fpTargetFolder']['0'] = 'Storage location for merged PDF templates';
$GLOBALS['TL_LANG']['tl_form']['fpTargetFolder']['1'] = 'Path where the filled PDF files are to be stored.';

$GLOBALS['TL_LANG']['tl_form']['fpNameTemplate']['0'] = 'Template for file name of the merged PDF';
$GLOBALS['TL_LANG']['tl_form']['fpNameTemplate']['1'] = 'The merged PDF is created with this file name in the target folder. Insert tags and SimpleTokens (##form_*##, ##formconfig_*##) can be used as placeholders.';

$GLOBALS['TL_LANG']['tl_form']['fpDoNotOverwrite']['0'] = 'Preserve existing files';
$GLOBALS['TL_LANG']['tl_form']['fpDoNotOverwrite']['1'] = 'Add a numeric suffix to the new file if the file name already exists.';

$GLOBALS['TL_LANG']['tl_form']['fpInsertTagPrefix']['0'] = 'Insert tag prefix';
$GLOBALS['TL_LANG']['tl_form']['fpInsertTagPrefix']['1'] = 'Prefix for values that are to be resolved as insert tags on the server side. Default=[[';

$GLOBALS['TL_LANG']['tl_form']['fpInsertTagSuffix']['0'] = 'Insert tag suffix';
$GLOBALS['TL_LANG']['tl_form']['fpInsertTagSuffix']['1'] = 'Suffix for values that are to be resolved as insert tags on the server side. Default=]]';

$GLOBALS['TL_LANG']['tl_form']['fpFlatten']['0'] = 'Flatten form fields';
$GLOBALS['TL_LANG']['tl_form']['fpFlatten']['1'] = 'If activated, all form fields are flattened (removed) from the PDF after the merge.';

$GLOBALS['TL_LANG']['tl_form']['fpLeadStore']['0'] = 'Save in leads';
$GLOBALS['TL_LANG']['tl_form']['fpLeadStore']['1'] = 'Select if/where the field value should be saved. For linked forms, you must select the matching main form field.';
