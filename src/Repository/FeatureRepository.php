<?php

declare(strict_types=1);

namespace Connector\Repository;

use Connector\Entity\Feature;
use Connector\Entity\Product;

class FeatureRepository extends GatewayRepository
{
    private string $tableName = 'feature';

    /**
     * @param Product $product
     * @return void
     */
    public function deleteAllByProduct(Product $product): void
    {
        $this->connection->delete($this->tableName, ['product_id' => $product->getId()]);
    }

    /**
     * @param Feature $feature
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(Feature $feature): bool
    {
        $sql = 'INSERT INTO '.$this->tableName.' (product_id, name, value)'
            .' VALUES(:product_id, :name, :value)'
            .' ON DUPLICATE KEY UPDATE value=:value, `updated_at`=NOW()';

        /** @var \Doctrine\DBAL\Driver\Statement $stmt */
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('product_id', $feature->getProduct()->getId());
        $stmt->bindValue('name', $feature->getName());
        $stmt->bindValue('value', $feature->getValue());

        return $stmt->execute();
    }
}
