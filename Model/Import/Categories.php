<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Import;

use Opengento\CategoryImportExport\Model\Config\Source\Category\Attributes;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Store\Model\StoreManagerInterface;

class Categories
{
    public const FORBIDDEN_FIELDS = [
        'all_children',
        'children',
        'children_count',
        'created_at',
        'created_in',
        'level',
        'parent_id',
        'path',
        'path_in_store',
        'url_path',
        'store_id',
        'store',
        'updated_at',
        'updated_in',
    ];

    public function __construct(
        private CollectionFactory $collectionFactory,
        private StoreManagerInterface $storeManager,
        private CategoryFactory $categoryFactory,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function execute(array $data): void
    {
        foreach ($this->batchByStore($data) as $storeId => $rows) {
            $this->processStore($storeId, $rows);
        }
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    private function processStore(int $storeId, array $rows): void
    {
        $collection = $this->createCollection($storeId, array_column($rows, 'category_code'));
        try {
            foreach ($rows as $row) {
                $category = $collection->getItemByColumnValue('category_code', $row['category_code'])
                    ?? $this->categoryFactory->create();
                $category->addData($row);
                $category->setStoreId($storeId);
                if (isset($row['parent_code'])) {
                    $category = $this->updateParent($category, $row['parent_code']);
                }
                $this->categoryRepository->save($category);
            }
        } catch (LocalizedException $e) {
            throw new LocalizedException(
                new Phrase(
                    'An error occurred while processing the category with code "%1" with the store "%2". The error is: %3',
                    [
                        $row['category_code'],
                        $this->storeManager->getStore($storeId)->getName(),
                        $e->getMessage()]
                )
            );
        }
    }

    /**
     * @throws NoSuchEntityException
     */
    private function batchByStore(array $data): array
    {
        $batch = [];
        foreach ($data as $row) {
            $storeId = $this->storeManager->getStore($row['store'] ?? 'admin')->getId();
            foreach (self::FORBIDDEN_FIELDS as $field) {
                unset($row[$field]);
            }
            $batch[$storeId][] = $row;
        }

        return $batch;
    }

    private function createCollection(int $storeId, array $codes): Collection
    {
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->setProductStoreId($storeId);
        $collection->setLoadProductCount(false);
        $collection->addFieldToFilter('category_code', ['in' => $codes]);

        return $collection;
    }

    /**
     * @throws LocalizedException
     */
    private function updateParent(Category $category, string $parentCode): Category
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('path');
        $collection->setLoadProductCount(false);
        $collection->addAttributeToFilter('category_code', $parentCode);

        /** @var Category $parentCategory */
        $parentCategory = $collection->getFirstItem();
        $parentId = (int)$parentCategory->getId();
        if ($parentId) {
            if ($category->getId()) {
                if ($parentId !== (int)$category->getParentId()) {
                    $category->move($parentId, null);
                }
            } else {
                $category->setParentId($parentId);
            }
        }

        return $category;
    }
}
