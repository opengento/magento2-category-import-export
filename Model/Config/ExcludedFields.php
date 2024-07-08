<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Config;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

use function array_merge;
use function array_unique;
use function explode;

class ExcludedFields
{
    private const CONFIG_PATH_EXCLUDE_FIELDS = 'catalog/category_import_export/exclude_fields';
    private const CONFIG_PATH_EXCLUDE_ATTRIBUTES = 'catalog/category_import_export/exclude_attributes';

    private ?array $excludedAttributes = null;

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private Config $config,
        private CollectionFactory $collectionFactory
    ) {}

    public function get(): array
    {
        return $this->excludedAttributes ??= array_unique(array_merge(
            ['category_code'],
            $this->resolveInvisibleAttributes(),
            explode(',', $this->scopeConfig->getValue(self::CONFIG_PATH_EXCLUDE_FIELDS) ?? ''),
            explode(',', $this->scopeConfig->getValue(self::CONFIG_PATH_EXCLUDE_ATTRIBUTES) ?? '')
        ));
    }

    private function resolveInvisibleAttributes(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->setEntityTypeFilter($this->config->getEntityType(Category::ENTITY));
        $collection->addFieldToSelect('attribute_code');
        $collection->joinLeft(
            ['cea' => 'catalog_eav_attribute'],
            'main_table.attribute_id = cea.attribute_id',
            ['']
        );
        $collection->addFieldToFilter('cea.is_visible', 0);

        return $collection->getColumnValues('attribute_code');
    }
}
