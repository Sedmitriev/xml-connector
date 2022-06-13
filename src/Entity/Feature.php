<?php

declare(strict_types=1);

namespace Connector\Entity;

class Feature
{
    private Product $product;

    private string $name;

    private string $value;

    private ?string $unit;

    private int $sort = 0;

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return Feature
     */
    public function setProduct(Product $product): Feature
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Feature
     */
    public function setName(string $name): Feature
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Feature
     */
    public function setValue(string $value): Feature
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param string|null $unit
     * @return Feature
     */
    public function setUnit(?string $unit): Feature
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return Feature
     */
    public function setSort(int $sort): Feature
    {
        $this->sort = $sort;

        return $this;
    }

}
