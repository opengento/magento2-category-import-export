<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Controller\Adminhtml\Export;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Phrase;
use Opengento\CategoryImportExport\Model\Session\DownloadContext;

use function basename;

class CategoryDownload extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_CategoryImportExport::export';

    public function __construct(
        Action\Context $context,
        private DownloadContext $downloadContext,
        private FileFactory $fileFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $file = $this->downloadContext->getFile();
        if ($file) {
            $this->downloadContext->clearFile();
            try {
                return $this->fileFactory->create(
                    basename($file),
                    ['type' => 'filename', 'value' => $file],
                    DirectoryList::VAR_IMPORT_EXPORT
                );
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, new Phrase('Something went wrong while downloading the file.'));
            }
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/category');
    }
}
