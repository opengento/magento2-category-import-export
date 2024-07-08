<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Controller\Adminhtml\Export;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Store\Model\Store;
use Opengento\CategoryImportExport\Model\Csv\Options;
use Opengento\CategoryImportExport\Model\Export\ToCsv;
use Opengento\CategoryImportExport\Model\Session\DownloadContext;

class CategoryPost extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_CategoryImportExport::export';

    public function __construct(
        Action\Context $context,
        private DownloadContext $downloadContext,
        private ToCsv $toCsv
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        try {
            $this->downloadContext->setFile(
                $this->toCsv->execute(
                    $this->resolveStoreIds(),
                    $this->resolveAttributes(),
                    Options::createFromRequest($this->getRequest())
                )
            );

            $this->messageManager->addSuccessMessage(new Phrase('The export file is now ready for download.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e, $e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('Something went wrong while exporting the data.'));
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/category');
    }

    private function resolveStoreIds(): array
    {
        return (array)$this->getRequest()->getParam('store_ids', [Store::DEFAULT_STORE_ID]);
    }

    /**
     * @throws InputException
     */
    private function resolveAttributes(): array
    {
        return (array)$this->getRequest()->getParam('attributes') ?: throw InputException::requiredField('attributes');
    }
}
