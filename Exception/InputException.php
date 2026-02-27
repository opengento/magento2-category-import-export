<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Exception;

use Magento\Framework\Phrase;
use Magento\Framework\Exception\InputException as MagentoInputException;

/**
 * Exception to be thrown when there is an issue with the Input to a function call.
 */
class InputException extends MagentoInputException
{
    /**
     * Creates an InputException for a missing required field.
     */
    public static function requiredField($fieldName, $entityId = null): MagentoInputException
    {
        if ($entityId) {
            $messsage = new Phrase(
                '"%fieldName" is required for entity "%entityId". Enter and try again.',
                [
                    'fieldName' => $fieldName,
                    'entityId' => $entityId
                ]
            );
        } else {
            $messsage = new Phrase(
                '"%fieldName" is required. Enter and try again.',
                ['fieldName' => $fieldName]
            );
        }

        return new self($messsage);
    }
}
