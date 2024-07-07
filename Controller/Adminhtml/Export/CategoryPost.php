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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
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
        $request = $this->getRequest();
        try {
            $this->downloadContext->setFile(
                $this->toCsv->execute(
                    (array)$request->getParam('store_ids'),
                    (array)$request->getParam('attributes'),
                    new Options(
                        (string)$request->getParam('delimiter'),
                        (string)$request->getParam('enclosure')
                    )
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
}
