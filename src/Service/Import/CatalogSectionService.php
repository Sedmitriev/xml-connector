<?php

declare(strict_types=1);

namespace Connector\Service\Import;

use Connector\Entity\CatalogSection;
use Connector\Repository\CatalogSectionRepository;

class CatalogSectionService
{
    private CatalogSectionRepository $catalogSectionRepository;

    /**
     * @param CatalogSectionRepository $catalogSectionRepository
     */
    public function __construct(CatalogSectionRepository $catalogSectionRepository)
    {
        $this->catalogSectionRepository = $catalogSectionRepository;
    }

    /**
     * @param array $xmlData
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function import(array $xmlData): void
    {
        $parentSectionId = null;
        if ($xmlData['parentId']) {
            $parentSection = $this->catalogSectionRepository->findById((int) $xmlData['parentId']);
            $parentSectionId = $parentSection ? $parentSection->getId() : null;
        }
        $catalogSection = new CatalogSection();
        $catalogSection
            ->setId((int) $xmlData['id'])
            ->setExternalId($xmlData['id'])
            ->setParentId($parentSectionId)
            ->setName($xmlData['name']);

        $this->catalogSectionRepository->save($catalogSection);
    }
}
