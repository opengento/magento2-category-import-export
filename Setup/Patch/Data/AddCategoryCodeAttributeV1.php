<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
namespace Opengento\CategoryImportExport\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zend_Validate_Exception;

class AddCategoryCodeAttributeV1 implements DataPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $setup,
        private EavSetupFactory $eavSetupFactory
    ) {}

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply(): self
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);

        $eavSetup->addAttribute(
            Category::ENTITY,
            'category_code',
            [
                'type' => 'varchar',
                'label' => 'Identifier',
                'input' => 'text',
                'source' => '',
                'visible' => true,
                'default' => null,
                'required' => true,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => false,
                'group' => '',
                'backend' => ''
            ]
        );

        return $this;
    }
}
