<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model;

use Magento\Framework\Exception\InputException;
use Opengento\CategoryImportExport\Model\Config\ExcludedFields;

use function array_diff_key;
use function array_flip;

class Utils
{
    public function __construct(private ExcludedFields $excludedFields) {}

    /**
     * @throws InputException
     */
    public function sanitizeData(array $data): array
    {
        $categoryCode = $data['category_code'] ?? throw InputException::requiredField('category_code');
        $data = array_diff_key($data, array_flip($this->excludedFields->get()));

        return ['category_code' => $categoryCode] + $data;
    }
}
