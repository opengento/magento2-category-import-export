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
    public function __construct(
        public readonly string $delimiter = ',',
        public readonly string $enclosure = '"'
    ) {}

    /**
     * @throws InputException
     */
    public static function createFromRequest(RequestInterface $request): Options
    {
        $delimiter = (string)$request->getParam('delimiter');
        $enclosure = (string)$request->getParam('enclosure');

        if (strlen($delimiter) !== 1) {
            throw new InputException(
                new Phrase(
                    'Invalid value of "%1" provided for the %2 field. Expected a single valid character.',
                    ['delimiter', $delimiter]
                )
            );
        }
        if (strlen($enclosure) !== 1) {
            throw new InputException(
                new Phrase(
                    'Invalid value of "%1" provided for the %2 field. Expected a single valid character.',
                    ['enclosure', $enclosure]
                )
            );
        }

        return new Options($delimiter, $enclosure);
    }
}
