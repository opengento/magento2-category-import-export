<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Model\Csv;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Phrase;

use function strlen;

class Options
{
    /**
     * @throws InputException
     */
    public function __construct(
        public readonly string $delimiter = ',',
        public readonly string $enclosure = '"'
    ) {
        if (strlen($this->delimiter) !== 1) {
            throw new InputException(
                new Phrase(
                    'Invalid value of "%1" provided for the %2 field. Expected a single valid character.',
                    ['delimiter', $this->delimiter]
                )
            );
        }
        if (strlen($this->enclosure) !== 1) {
            throw new InputException(
                new Phrase(
                    'Invalid value of "%1" provided for the %2 field. Expected a single valid character.',
                    ['enclosure', $this->enclosure]
                )
            );
        }
    }

    /**
     * @throws InputException
     */
    public static function createFromRequest(RequestInterface $request): Options
    {
        return new Options((string)$request->getParam('delimiter'), (string)$request->getParam('enclosure'));
    }
}
