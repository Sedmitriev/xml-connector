<?php

declare(strict_types=1);

namespace Connector\Entity;

use Doctrine\ORM\Mapping as ORM;

class Product
{
//    /**
//     * @ORM\Id()
//     * @ORM\GeneratedValue()
//     * @ORM\Column(type="integer")
//     */
    private ?int $id;

//    /**
//     * @ORM\Column(type="string", length=120)
//     */
    private string $externalId;

//    /**
//     * @ORM\Column(type="string", length=60)
//     */
    private string $article;

//    /**
//     * @ORM\Column(type="string", length=80)
//     */
    private ?string $manufacturerCode;

//    /**
//     * @ORM\Column(type="string", length=80)
//     */
    private ?string $altManufacturerCode;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
    private ?string $manufacturer;

//    /**
//     * @ORM\Column(type="text")
//     */
    private ?string $name;

//    /**
//     * @ORM\Column(type="text")
//     */
    private ?string $nameOfManufacturer;

//    /**
//     * @ORM\Column(type="text")
//     */
    private ?string $altNameOfManufacturer;

//    /**
//     * @ORM\Column(type="string", length=30)
//     */
    private string $unitName = 'шт.';

//    /**
//     * @ORM\Column(type="float", scale="4")
//     */
    private float $multiplicity = 1.0;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
    private ?string $imageUrl;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
    private ?string $countryCodeA3;

//    /**
//     * @ORM\Column(type="string", length=1)
//     */
    private ?string $stockStatus;

//    /**
//     * @ORM\ManyToOne(targetEntity="CatalogSection", cascade={"persist"})
//     * @ORM\JoinColumn(name="catalog_section_id", nullable=true, onDelete="SET NULL")
//     */
    private ?CatalogSection $catalogSection;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Product
     */
    public function setId(?int $id): Product
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     * @return Product
     */
    public function setExternalId(string $externalId): Product
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * @param string $article
     * @return Product
     */
    public function setArticle(string $article): Product
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getManufacturerCode(): ?string
    {
        return $this->manufacturerCode;
    }

    /**
     * @param string|null $manufacturerCode
     * @return Product
     */
    public function setManufacturerCode(?string $manufacturerCode): Product
    {
        $this->manufacturerCode = $manufacturerCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAltManufacturerCode(): ?string
    {
        return $this->altManufacturerCode;
    }

    /**
     * @param string|null $altManufacturerCode
     * @return Product
     */
    public function setAltManufacturerCode(?string $altManufacturerCode): Product
    {
        $this->altManufacturerCode = $altManufacturerCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    /**
     * @param string|null $manufacturer
     * @return Product
     */
    public function setManufacturer(?string $manufacturer): Product
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Product
     */
    public function setName(?string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameOfManufacturer(): ?string
    {
        return $this->nameOfManufacturer;
    }

    /**
     * @param string|null $nameOfManufacturer
     * @return Product
     */
    public function setNameOfManufacturer(?string $nameOfManufacturer): Product
    {
        $this->nameOfManufacturer = $nameOfManufacturer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAltNameOfManufacturer(): ?string
    {
        return $this->altNameOfManufacturer;
    }

    /**
     * @param string|null $altNameOfManufacturer
     * @return Product
     */
    public function setAltNameOfManufacturer(?string $altNameOfManufacturer): Product
    {
        $this->altNameOfManufacturer = $altNameOfManufacturer;

        return $this;
    }

    /**
     * @return string
     */
    public function getUnitName(): string
    {
        return $this->unitName;
    }

    /**
     * @param string $unitName
     * @return Product
     */
    public function setUnitName(string $unitName): Product
    {
        $this->unitName = $unitName;

        return $this;
    }

    /**
     * @return float
     */
    public function getMultiplicity(): float
    {
        return $this->multiplicity;
    }

    /**
     * @param float $multiplicity
     * @return Product
     */
    public function setMultiplicity(float $multiplicity): Product
    {
        $this->multiplicity = $multiplicity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     * @return Product
     */
    public function setImageUrl(?string $imageUrl): Product
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCodeA3(): ?string
    {
        return $this->countryCodeA3;
    }

    /**
     * @param string|null $countryCodeA3
     * @return Product
     */
    public function setCountryCodeA3(?string $countryCodeA3): Product
    {
        $this->countryCodeA3 = $countryCodeA3;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStockStatus(): ?string
    {
        return $this->stockStatus;
    }

    /**
     * @param string|null $stockStatus
     * @return Product
     */
    public function setStockStatus(?string $stockStatus): Product
    {
        $this->stockStatus = $stockStatus;

        return $this;
    }

    /**
     * @return CatalogSection|null
     */
    public function getCatalogSection(): ?CatalogSection
    {
        return $this->catalogSection;
    }

    /**
     * @param CatalogSection|null $catalogSection
     * @return Product
     */
    public function setCatalogSection(?CatalogSection $catalogSection): Product
    {
        $this->catalogSection = $catalogSection;

        return $this;
    }

}
