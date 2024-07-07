<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Csv;

class Options
{
    public function __construct(
        public readonly string $delimiter = ',',
        public readonly string $enclosure = '"'
    ) {}
}
