<?php

declare(strict_types=1);

namespace Cgoit\FormFillPdfBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;

#[AsHook('processFormData')]
class ProcessFormDataListener
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @param array<mixed>      $submittedData
     * @param array<mixed>      $formConfig
     * @param array<mixed>|null $files
     */
    public function __invoke(array $submittedData, array $formConfig, array|null $files): void
    {
        if (!empty($formConfig['fpFill']) && !empty($files)) {
            $configs = StringUtil::deserialize($formConfig['fpConfigs']);
            $shouldStore = array_filter($configs, static fn ($config) => !empty($config['fpLeadStore']));

            if (!empty($shouldStore)) {
                $leadId = $this->db->fetchAssociative('SELECT id FROM tl_lead WHERE post_data=?', [serialize($submittedData)]);

                if (false !== $leadId) {
                    $leadId = $leadId['id'];

                    foreach ($shouldStore as $pdf) {
                        $fpName = $pdf['fpName'];

                        if (!empty($files[$fpName])) {
                            $file = $files[$fpName];

                            $data = [
                                'pid' => $leadId,
                                'tstamp' => time(),
                                'name' => $fpName,
                                'value' => $file['uuid'] ?? $file['name'],
                                'label' => $file['full_path'],
                            ];

                            $this->db->insert('tl_lead_data', $data);
                        }
                    }
                }
            }
        }
    }
}
