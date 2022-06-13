<?php

declare(strict_types=1);

namespace Connector\Repository;

use Connector\Entity\Price;
use Connector\Entity\Product;

class PriceRepository extends GatewayRepository
{
    private string $tableName = 'price';

    /**
     * @param Product $product
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function deleteAllByProduct(Product $product): void
    {
        $this->connection->delete($this->tableName, ['product_id' => $product->getId()]);
    }

    /**
     * @param Price $price
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(Price $price): bool
    {
        $sql = 'INSERT INTO ' . $this->tableName . ' (`product_id`, `type_id`, `price`)'
            . ' VALUES(:product_id, :type_id, :price)'
            . 'ON DUPLICATE KEY UPDATE `price`=:price, `updated_at`=NOW()';

        $product = $price->getProduct();
        /** @var \Doctrine\DBAL\Driver\Statement $stmt */
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('product_id', $product->getId());
        $stmt->bindValue('type_id', Price::DEFAULT_PRICE_TYPE_ID);
        $stmt->bindValue('price', $price->getPrice());

        return $stmt->execute();
    }
}
