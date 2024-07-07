<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Setup\Patch\Data;

use Exception;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category as ResourceCategory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class PopulateCategoriesIdentifierV1 implements DataPatchInterface
{
    public function __construct(
        private CollectionFactory $collecionFactory,
        private ResourceCategory $resourceCategory
    ) {}

    public static function getDependencies(): array
    {
        return [AddCategoryCodeAttributeV1::class];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function apply(): void
    {
        $collection = $this->collecionFactory->create();
        $categories = $collection->addAttributeToSelect([
            'entity_id',
            'description',
            'name',
            'url',
            'category_code',
            'path',
            'path_ids',
            'url_key',
            'is_active',
            'include_in_menu',
            'meta_title',
            'store_id',
            'parent_id'
        ])->getItems();

        $idsToName = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            $idsToName[$category->getId()] = $category->getName();
        }

        /** @var Category $category */
        foreach ($categories as $category) {
            if ($category->getId() !== '1' && !$category->getData('category_code')) {
                $code = '';
                foreach($category->getPathIds() as $pathId) {
                    if ($pathId !== '1') {
                        if ($code === '') {
                            $code = $idsToName[$pathId];
                        } else {
                            $code .= '_' . $idsToName[$pathId];
                        }
                    }
                }

                $category->setCustomAttribute('category_code', strtolower(str_replace(' ', '_', $code)));
                $this->resourceCategory->saveAttribute($category, 'category_code');
            }
        }
    }
}
