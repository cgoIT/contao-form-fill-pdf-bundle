<?php

declare(strict_types=1);

/*
 * This file is part of cgoit\contao-form-fill-pdf-bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2024, cgoIT
 * @author     cgoIT <https://cgo-it.de>
 * @license    LGPL-3.0-or-later
 */

namespace Cgoit\FormFillPdfBundle\Widget;

use Contao\BackendTemplate;
use Contao\System;
use Contao\Widget;

class GeneratePdf extends Widget
{
    public const string TYPE = 'fp_generate_pdf';

    /**
     * Submit indicator.
     *
     * @var bool
     */
    protected $blnSubmitInput = true;

    /**
     * Do not validate this form field.
     */
    public function validator(mixed $input): mixed
    {
        return $input;
    }

    /**
     * @param array<mixed>|null $attributes
     *
     * @return string
     */
    public function parse($attributes = null)
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $template = new BackendTemplate('be_wildcard');
            $template->wildcard = '### GENERATE PDF ###';

            return $template->parse();
        }

        return '';
    }

    /**
     * Old generate() method that must be implemented due to abstract declaration.
     *
     * @throws \BadMethodCallException
     */
    public function generate(): string
    {
        throw new \BadMethodCallException('Calling generate() has been deprecated, you must use parse() instead!');
    }
}
