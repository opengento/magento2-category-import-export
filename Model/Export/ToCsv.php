<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Export;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Opengento\CategoryImportExport\Model\Csv\Options;

class ToCsv
{
    public function __construct(
        private Categories $categories,
        private Filesystem $filesystem,
        private Csv $csv
    ) {}

    /**
     * @throws NoSuchEntityException
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function execute(array $storeIds, array $attributes, Options $options): string
    {
        $directoryWrite = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_IMPORT_EXPORT);
        $directoryWrite->create('export');
        $fileName = 'export/' . time() . '-categories.csv';

        $batch = [];
        foreach ($storeIds as $storeId) {
            $batch[] = $this->categories->execute((int)$storeId, $attributes);
        }
        $data = array_merge([], ...$batch);
        array_unshift($data, array_keys($data[0] ?? []));

        $this->csv->setDelimiter($options->delimiter);
        if ($options->enclosure !== null) {
            $this->csv->setEnclosure($options->enclosure);
        }
        $this->csv->appendData($directoryWrite->getAbsolutePath($fileName), $data);

        return $directoryWrite->getRelativePath($fileName);
    }
}
