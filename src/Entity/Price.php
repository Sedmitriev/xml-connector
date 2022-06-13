<?php

declare(strict_types=1);

namespace Connector\Entity;

class Price
{
    const DEFAULT_PRICE_TYPE_ID = 1;

    private Product $product;

    private int $priceTypeId = self::DEFAULT_PRICE_TYPE_ID;

    private float $price;

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Price
     */
    public function setProduct(Product $product): Price
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceTypeId(): int
    {
        return $this->priceTypeId;
    }

    /**
     * @param int $priceTypeId
     * @return Price
     */
    public function setPriceTypeId(int $priceTypeId): Price
    {
        $this->priceTypeId = $priceTypeId;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Price
     */
    public function setPrice(float $price): Price
    {
        $this->price = $price;

        return $this;
    }
}
