<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
namespace Opengento\CategoryImportExport\Model\Import;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Csv;
use Magento\Framework\Phrase;
use Opengento\CategoryImportExport\Model\Csv\Options;

use function array_combine;
use function array_map;
use function array_shift;
use function count;

class FromCsv
{
    public function __construct(
        private Csv $csv,
        private Categories $categories
    ) {}

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws InputException
     * @throws Exception
     */
    public function execute(string $filePath, Options $options): void
    {
        $this->csv->setDelimiter($options->delimiter);
        if ($options->enclosure !== null) {
            $this->csv->setEnclosure($options->enclosure);
        }

        $rows = $this->csv->getData($filePath);
        $keys = array_map(static fn (string $key): string => preg_replace('/\W/', '', $key), array_shift($rows));
        $keysCount = count($keys);

        $data = [];
        foreach ($rows as $row) {
            if ($keysCount !== count($row)) {
                throw new InputException(
                    new Phrase('The number of column does not match the keys. Please verify the field separator.')
                );
            }
            $data[] = array_combine($keys, $row);
        }

        $this->categories->execute($data);
    }
}
