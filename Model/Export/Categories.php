<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Export;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Opengento\CategoryImportExport\Model\Utils;

use function array_unshift;

class Categories
{
    public function __construct(
        private CollectionFactory $collectionFactory,
        private StoreManagerInterface $storeManager,
        private Utils $utils
    ) {
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws InputException
     */
    public function execute(int $storeId, array $attributes): array
    {
        array_unshift($attributes, 'category_code', 'parent_id');

        $store = $this->storeManager->getStore($storeId);

        // Get the root category to derive the path prefix
        $rootCategoryId = (int) $store->getRootCategoryId();

        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->setProductStoreId($storeId);
        $collection->setLoadProductCount(false);
        $collection->addAttributeToSelect($attributes);

        $collection->addPathsFilter("1/{$rootCategoryId}");

        // Sort by level so parents are always in $parents[] before children
        $collection->addAttributeToSort('level', 'ASC');

        $parents = [];
        $export = [];
        /** @var Category $category */
        foreach ($collection->getItems() as $category) {
            $parents[$category->getId()] ??= $category;
            $parentCategory = $parents[$category->getParentId()] ?? null;
            $row = $this->utils->sanitizeData($category->toArray($attributes));
            $row['store'] = $store->getCode();
            $row['parent_code'] = $parentCategory?->getData('category_code');
            $export[] = $row;
        }

        return $export;
    }
}
