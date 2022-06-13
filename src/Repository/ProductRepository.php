<?php

declare(strict_types=1);

namespace Connector\Repository;

use Connector\Entity\CatalogSection;
use Connector\Entity\Product;

class ProductRepository extends GatewayRepository
{
    private string $tableName = 'product';

    /**
     * @param string $externalId
     * @return Product|null
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByExternalId(string $externalId): ?Product
    {
        $sql = 'SELECT p.* FROM '.$this->tableName.' p WHERE p.external_id = :external_id';
        $product = $this->connection->executeQuery($sql, ['external_id' => $externalId])->fetchAssociative();

        return $product ? $this->createGatewayObject($product) : null;
    }

    /**
     * @param Product $product
     * @param bool $insertMode
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(Product &$product, bool $insertMode = true): bool
    {
        $sql = 'INSERT INTO '.$this->tableName.' (external_id, article, name, multiplicity, catalog_section_id, manufacturer_code, manufacturer, image_url, unit_name)'
            .' VALUES(:external_id, :article, :name, :multiplicity, :catalog_section_id, :manufacturer_code, :manufacturer, :image_url, :unit_name)';
        if (!$insertMode) {
            $sql = 'UPDATE '.$this->tableName.' SET `external_id`=:external_id, `article`=:article, `name`=:name, `multiplicity`=:multiplicity,'
                .' `catalog_section_id`=:catalog_section_id, `manufacturer_code`=:manufacturer_code, `manufacturer`=:manufacturer, `image_url`=:image_url, `unit_name`=:unit_name'
                .' WHERE `id`=:id';
        }

        /** @var \Doctrine\DBAL\Driver\Statement $stmt */
        $stmt = $this->connection->prepare($sql);
        if (!$insertMode) {
            $stmt->bindValue('id', $product->getId());
        }
        $stmt->bindValue('external_id', $product->getExternalId());
        $stmt->bindValue('article', $product->getArticle());
        $stmt->bindValue('name', $product->getName());
        $stmt->bindValue('multiplicity', $product->getMultiplicity());
        $stmt->bindValue('manufacturer_code', $product->getManufacturerCode());
        $stmt->bindValue('manufacturer', $product->getManufacturer());
        $stmt->bindValue('image_url', $product->getImageUrl());
        $stmt->bindValue('unit_name', $product->getUnitName());

        $catalogSection = $product->getCatalogSection();
        $stmt->bindValue('catalog_section_id', $catalogSection ? $catalogSection->getId() : null);

        try {
            $saveResult = $stmt->execute();
            if ($insertMode) {
                $product->setId((int) $this->connection->lastInsertId());
            }

            return $saveResult;
        } catch (\Exception $e) {
            echo sprintf(
                'Throw exception: "%s" on %s'.PHP_EOL,
                $e->getMessage(),
                date("Y.m.d H:i:s")
            );
            //print_r($e->getTrace());
        }

        return false;
    }

    /**
     * @param array $data
     * @return Product
     */
    private function createGatewayObject(array $data)
    {
        $multiplicity = (float) $data['multiplicity'];
        $product = new Product();
        $product
            ->setId((int) $data['id'])
            ->setExternalId($data['external_id'])
            ->setArticle($data['article'])
            ->setManufacturerCode($data['manufacturer_code'])
            ->setName($data['name'])
            ->setUnitName($data['unit_name'])
            ->setMultiplicity($multiplicity ?? 1.0)
            ->setImageUrl($data['image_url']);

        if ($data['catalog_section_id']) {
            $catalogSection = new CatalogSection();
            $catalogSection->setId((int) $data['catalog_section_id']);
            $product->setCatalogSection($catalogSection);
        }

        return $product;
    }
}
