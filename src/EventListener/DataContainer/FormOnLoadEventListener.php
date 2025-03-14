<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2025, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

namespace Cgoit\FormFillPdfBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;

#[AsCallback(table: 'tl_form', target: 'config.onload')]
class FormOnLoadEventListener
{
    public function __invoke(DataContainer $dc): void
    {
        if (class_exists('Terminal42\LeadsBundle\Terminal42LeadsBundle')) {
            $GLOBALS['TL_DCA'][$dc->table]['fields']['fpConfigs']['palette'][] = 'fpLeadStore';
        }
    }
}
