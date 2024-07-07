<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Session;

use Magento\Framework\App\Request\DataPersistorInterface;

class DownloadContext
{
    private const FILE_KEY = 'opengento_category_export_file';

    public function __construct(private DataPersistorInterface $dataPersistor) {}

    public function getFile(): string
    {
        return (string)$this->dataPersistor->get(self::FILE_KEY);
    }

    public function setFile(string $file): void
    {
        $this->dataPersistor->set(self::FILE_KEY, $file);
    }

    public function clearFile(): void
    {
        $this->dataPersistor->clear(self::FILE_KEY);
    }
}
