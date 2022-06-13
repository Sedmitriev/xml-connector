<?php

declare(strict_types=1);

namespace Connector\Repository;

use Connector\Entity\CatalogSection;

class CatalogSectionRepository extends GatewayRepository
{
    private string $tableName = 'catalog_section';

    /**
     * @param int $id
     * @return CatalogSection|null
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findById(int $id): ?CatalogSection
    {
        $sql = 'SELECT cs.* FROM '.$this->tableName.' cs WHERE cs.id = '. $id;
        $catalogSection = $this->connection->executeQuery($sql)->fetchAssociative();

        return $catalogSection ? $this->createGatewayObject($catalogSection) : null;
    }

    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function deleteAll()
    {
        $sql = 'DELETE FROM '.$this->tableName;
        /** @var \Doctrine\DBAL\Driver\Statement $stmt */
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute();
    }

    /**
     * @param CatalogSection $catalogSection
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(CatalogSection $catalogSection): bool
    {
        $sql = 'INSERT INTO '.$this->tableName.' (id, name, parent_id)'
            .' VALUES(:id, :name, :parent_id)'
            .' ON DUPLICATE KEY UPDATE name=:name, parent_id=:parent_id, updated_at=NOW()';

        /** @var \Doctrine\DBAL\Driver\Statement $stmt */
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $catalogSection->getId());
        $stmt->bindValue('name', $catalogSection->getName());
        $parentId = $catalogSection->getParentId();
        $stmt->bindValue('parent_id', $parentId ? $parentId : null);

        return $stmt->execute();
    }

    /**
     * @param array $data
     * @return CatalogSection
     */
    private function createGatewayObject(array $data)
    {
        $catalogSection = new CatalogSection();
        $catalogSection
            ->setId((int) $data['id'])
            ->setExternalId($data['external_id'])
            ->setName($data['name'])
            ->setParentId((int) $data['parent_id']);

        return $catalogSection;
    }
}
