<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as BaseDataProvider;

class DataProvider extends BaseDataProvider
{
    public function getData(): array
    {
        return [];
    }
}
