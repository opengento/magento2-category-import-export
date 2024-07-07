<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Config;

class CategoryAttribute
{
    public function getInvisibleAttributes(): array
    {
        //ToDo from collection
        return [
            'all_children',
            'automatic_sorting',
            'children',
            'children_count',
            'custom_layout_update',
            'level',
            'path',
            'path_in_store',
            'position',
            'url_path',
        ];
    }

    public function getForbiddenAttributes(): array
    {
        //ToDo from configuration (forbid invisible: yes, forbid list: <multiselect> see existing source)
        $attributes = $this->getInvisibleAttributes();
        $attributes += [
            'created_at',
            'created_in',
            'parent_id',
            'store_id',
            'store',
            'updated_at',
            'updated_in',
        ];

        return $attributes;
    }
}
