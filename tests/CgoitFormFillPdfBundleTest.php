<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2023, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

namespace Cgoit\FormFillPdfBundle\Tests;

use Cgoit\FormFillPdfBundle\CgoitFormFillPdfBundle;
use PHPUnit\Framework\TestCase;

class CgoitFormFillPdfBundleTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new CgoitFormFillPdfBundle();

        $this->assertInstanceOf('Cgoit\FormFillPdfBundle\CgoitFormFillPdfBundle', $bundle);
    }
}
