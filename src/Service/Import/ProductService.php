<?php

namespace Connector\Service\Import;

use Connector\Entity\Feature;
use Connector\Entity\Price;
use Connector\Entity\Product;
use Connector\Entity\ProductAdditionalField;
use Connector\Entity\ProductIdentifiers;
use Connector\Repository\CatalogSectionRepository;
use Connector\Repository\FeatureRepository;
use Connector\Repository\PriceRepository;
use Connector\Repository\ProductAdditionalFieldRepository;
use Connector\Repository\ProductIdentifiersRepository;
use Connector\Repository\ProductRepository;
use Doctrine\ORM\ORMException;

class ProductService
{
    private ProductRepository $productRepository;

    private FeatureRepository $featureRepository;

    private CatalogSectionRepository $catalogSectionRepository;

    private PriceRepository $priceRepository;

    /**
     * @param ProductRepository $productRepository
     * @param FeatureRepository $featureRepository
     * @param CatalogSectionRepository $catalogSectionRepository
     * @param PriceRepository $priceRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        FeatureRepository $featureRepository,
        CatalogSectionRepository $catalogSectionRepository,
        PriceRepository $priceRepository
    ) {
        $this->productRepository = $productRepository;
        $this->featureRepository = $featureRepository;
        $this->catalogSectionRepository = $catalogSectionRepository;
        $this->priceRepository = $priceRepository;
    }

    /**
     * @param array $xmlData
     * @return void
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function import(array $xmlData): void
    {
        $insertMode = false;
        $product = $this->productRepository->findByExternalId($xmlData['externalId']);
        if (!$product) {
            $product = new Product();
            $insertMode = true;
        }

        $product
            ->setExternalId($xmlData['externalId'])
            ->setArticle($xmlData['article'])
            ->setName($xmlData['name'])
            ->setManufacturerCode($xmlData['manufacturerCode'])
            ->setManufacturer($xmlData['manufacturer'])
            ->setImageUrl($xmlData['imageUrl']);
        $catalogSection = null;
        if ($xmlData['catalogSectionId']) {
            $catalogSection = $this->catalogSectionRepository->findById((int) $xmlData['catalogSectionId']);
        }
        $product->setCatalogSection($catalogSection);
        $this->productRepository->save($product, $insertMode);

        $this->importFeatures($product, $xmlData['features']);

        if ($xmlData['price']) {
            $this->importPrice($product, (float) $xmlData['price']);
        }
    }

    /**
     * @param Product $product
     * @param array $features
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function importFeatures(Product $product, array $features): void
    {
        if ($features) {
            foreach ($features as $name => $value) {
                $feature = new Feature();
                $feature
                    ->setName($name)
                    ->setValue($value)
                    ->setProduct($product);

                $this->featureRepository->save($feature);
            }
        }
    }

    /**
     * @param Product $product
     * @param float $price
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function importPrice(Product $product, float $price): void
    {
        $priceModel = new Price();
        $priceModel
            ->setProduct($product)
            ->setPrice($price);

        $this->priceRepository->save($priceModel);
    }

    /**
     * @param Product $product
     * @param array $productAdditionalFields
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function importProductAdditionalField(Product $product, array $productAdditionalFields)
    {
        foreach ($productAdditionalFields as $name => $value) {
            $productAdditionalField = new ProductAdditionalField();
            $productAdditionalField
                ->setName($name)
                ->setValue($value)
                ->setProduct($product);

            $this->productAdditionalFieldRepository->save($productAdditionalField);
        }
    }

    /**
     * @param Product $product
     * @param array $productIdentifiers
     * @return void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function importProductIdentifiers(Product $product, array $productIdentifiers)
    {
        foreach ($productIdentifiers as $name => $value) {
            $productIdentifier = new ProductIdentifiers();
            $productIdentifier
                ->setName($name)
                ->setValue($value)
                ->setProduct($product);

            $this->productIdentifiersRepository->save($productIdentifier);
        }
    }
}
