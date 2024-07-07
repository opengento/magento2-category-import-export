<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Config\Source\Category;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Attribute;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

class Attributes implements OptionSourceInterface
{
    private const EXCLUDE_ATTRIBUTES = [
        'all_children',
        'category_code',
        'children',
        'children_count',
        'level',
        'path',
        'path_in_store',
        'url_path',
    ];

    private ?array $options = null;

    public function __construct(
        private Config $config,
        private CollectionFactory $collectionFactory
    ) {}

    /**
     * @throws LocalizedException
     */
    public function toOptionArray(): array
    {
        return $this->options ??= $this->resolveAttributes();
    }

    /**
     * @throws LocalizedException
     */
    private function resolveAttributes(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->setEntityTypeFilter($this->config->getEntityType(Category::ENTITY));
        $collection->addFieldToSelect(['attribute_code', 'frontend_label']);
        $collection->addFieldToFilter('attribute_code', ['nin' => self::EXCLUDE_ATTRIBUTES]);
        $collection->setOrder('frontend_label', 'ASC');
        $collection->setOrder('attribute_code', 'ASC');

        $options = [];
        /** @var Attribute $attribute */
        foreach ($collection->getItems() as $attribute) {
            $options[] = [
                'label' => sprintf('%s (%s)', $attribute->getDefaultFrontendLabel(), $attribute->getAttributeCode()),
                'value' => $attribute->getAttributeCode()
            ];
        }

        return $options;
    }
}
