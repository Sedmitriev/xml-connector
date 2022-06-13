<?php

declare(strict_types=1);

namespace Connector\Entity;


class CatalogSection
{
    private int $id;

    private ?string $externalId;

    private string $name;

    private ?int $parentId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CatalogSection
     */
    public function setId(int $id): CatalogSection
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     * @return CatalogSection
     */
    public function setExternalId(?string $externalId): CatalogSection
    {
        $this->externalId = $externalId;

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
     * @return CatalogSection
     */
    public function setName(string $name): CatalogSection
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @param int|null $parentId
     * @return CatalogSection
     */
    public function setParentId(?int $parentId): CatalogSection
    {
        $this->parentId = $parentId;

        return $this;
    }
}
