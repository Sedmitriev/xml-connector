<?php

declare(strict_types=1);

namespace Connector\Service;

use Iterator;
use XMLReader;
use Exception;
use SimpleXMLElement;

class XmlParser
{
    private const OFFER_ELEMENT_NAME = 'offer';
    private const CATEGORY_ELEMENT_NAME = 'category';
    private const STORE_ELEMENT_NAME = 'outlet';
    private const REMAINS_ELEMENT_NAME = 'product';

    private string $filename;

    private XMLReader $xmlReader;

    /**
     * XmlParser constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->xmlReader = new XMLReader();
        $this->filename = $filename;
    }

    /**
     * @return Iterator
     */
    public function iterateProductData(): Iterator
    {
        foreach ($this->offerIterator() as $offerElement) {
            $data = [];
            // Определение externalId
            /** @var SimpleXMLElement $attribute */
            foreach ($offerElement->attributes() as $attribute) {
                if ($attribute->getName() === 'uuid') {
                    $data['externalId'] = (string) $attribute;
                    break;
                }
            }

            /** @var SimpleXMLElement $paramElement */
            foreach($offerElement->children() as $paramElement) {
                if ($paramElement->getName() === 'param') {
                    // Свойства товара
                    /** @var SimpleXMLElement $attribute */
                    foreach ($paramElement->attributes() as $paramAttribute) {
                        if ($paramAttribute->getName() === 'name') {
                            $data['features'][(string) $paramAttribute] = (string) $paramElement;
                            break;
                        }
                    }

                    continue;
                }

                // Атрибуты товара
                $data[$paramElement->getName()] = (string) $paramElement;
            }

            yield $this->normalizeData($data);
        }
    }

    /**
     * @return Iterator
     */
    private function offerIterator(): Iterator
    {
        $this->openReader();

        while ($this->xmlReader->read() && $this->xmlReader->name !== self::OFFER_ELEMENT_NAME);

        $n = 0;
        while ($this->xmlReader->name === self::OFFER_ELEMENT_NAME) {
            try {
                $outerXML = html_entity_decode($this->xmlReader->readOuterXML());
                $this->normalizeXml($outerXML);

                yield new SimpleXMLElement($outerXML);
            } catch (Exception $e) {
                $n++;
            }

            $this->xmlReader->next(self::OFFER_ELEMENT_NAME);
        }

        $this->closeReader();
    }

    /**
     * @return array
     */
    public function findAllProperties(): array
    {
        $properties = [];
        /** @var SimpleXMLElement $xmlElement */
        foreach ($this->offerIterator() as $xmlElement) {
            foreach($xmlElement->children() as $paramElement) {
                if ($paramElement->getName() !== 'param') {
                    continue;
                }
                foreach ($paramElement->attributes() as $attribute) {
                    if ($attribute->getName() === 'name') {
                        $property = (string) $attribute;
                        if (!in_array($property, $properties)) {
                            array_push($properties, $property);
                        }
                    }
                }
            }
        }

        return $properties;
    }

    /**
     * @return Iterator
     */
    public function catalogSectionsIterator(): Iterator
    {
        $this->openReader();

        while ($this->xmlReader->read() && $this->xmlReader->name !== self::CATEGORY_ELEMENT_NAME);

        while ($this->xmlReader->name === self::CATEGORY_ELEMENT_NAME) {
            $id = $this->xmlReader->getAttribute('id');
            $parentId = $this->xmlReader->getAttribute('parentId');
            $name = $this->xmlReader->readString();

            yield [
                'id' => $id,
                'parentId' => $parentId,
                'name' => $name
            ];

            $this->xmlReader->next(self::CATEGORY_ELEMENT_NAME);
        }

        $this->closeReader();
    }

    /**
     * @param array $xmlData
     * @return array
     */
    private function normalizeData(array $xmlData): array
    {
        $article = null;
        if (isset($xmlData['features']['Артикул'])) {
            $article = $xmlData['features']['Артикул'];
            unset($xmlData['features']['Артикул']);
        }

        return [
            'externalId' => $xmlData['externalId'],
            'name' => $xmlData['model'],
            'article' => $article,
            'manufacturerCode' => $xmlData['vendorCode'] ?? null,
            'manufacturer' => $xmlData['vendor'] ?? null,
            'imageUrl' => $xmlData['picture'] ?? null,
            'price' => $xmlData['price'] ?? null,
            'features' => $xmlData['features'],
            'catalogSectionId' => $xmlData['categoryId'] ?? null
        ];
    }

    /**
     * @param string $xmlString
     */
    private function normalizeXml(string &$xmlString): void
    {

    }

    private function openReader(): void
    {
        $this->xmlReader->open($this->filename);
    }

    private function closeReader(): void
    {
        $this->xmlReader->close();
    }
}
