<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2023, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

namespace Cgoit\FormFillPdfBundle\ContaoManager;

use Cgoit\FormFillPdfBundle\CgoitFormFillPdfBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Terminal42\LeadsBundle\Terminal42LeadsBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        $arrLoadAfter = [ContaoCoreBundle::class];

        if (class_exists(Terminal42LeadsBundle::class)) {
            $arrLoadAfter[] = Terminal42LeadsBundle::class; // @phpstan-ignore-line
        }

        return [
            BundleConfig::create(CgoitFormFillPdfBundle::class)
                ->setLoadAfter($arrLoadAfter),
        ];
    }
}
