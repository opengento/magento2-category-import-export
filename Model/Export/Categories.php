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
    ) {}

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws InputException
     */
    public function execute(int $storeId, array $attributes): array
    {
        array_unshift($attributes, 'category_code', 'parent_id');
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->setProductStoreId($storeId);
        $collection->setLoadProductCount(false);
        $collection->addAttributeToSelect($attributes);
        $collection->addAttributeToFilter('parent_id', ['neq' => 0]);

        $export = [];
        /** @var Category $category */
        foreach ($collection->getItems() as $category) {
            $row = $this->utils->sanitizeData($category->toArray($attributes));
            $row['store'] = $this->storeManager->getStore($storeId)->getCode();
            $row['parent_code'] = $category->getParentCategory()->getData('category_code');
            $export[] = $row;
        }

        return $export;
    }
}
