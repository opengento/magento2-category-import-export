<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Phrase;
use Magento\Framework\View\Result\Page;

class Category extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_CategoryImportExport::export';

    public function execute(): Page
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->set(new Phrase('Export Categories'));

        return $page;
    }
}
