<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Controller\Adminhtml\Import;

use Opengento\CategoryImportExport\Model\Csv\Options;
use Opengento\CategoryImportExport\Model\Import\FromCsv;
use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Phrase;

class CategoryPost extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Opengento_CategoryImportExport::import';

    public function __construct(
        Action\Context $context,
        private UploaderFactory $uploaderFactory,
        private Filesystem $filesystem,
        private FromCsv $fromCsv
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        try {
            $this->fromCsv->execute(
                $this->uploadFile('import-' . time() . '-categories.csv'),
                new Options(
                    (string)$this->getRequest()->getParam('delimiter'),
                    (string)$this->getRequest()->getParam('enclosure')
                )
            );
            $this->messageManager->addSuccessMessage(new Phrase('Import successful!'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('Something went wrong while uploading the file.'));
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/category');
    }

    /**
     * @throws Exception
     */
    private function uploadFile(string $fileName): string
    {
        $directoryRead = $this->filesystem->getDirectoryRead(DirectoryList::VAR_IMPORT_EXPORT);
        $uploader = $this->uploaderFactory->create(['fileId' => 'file']);
        $uploader->setAllowCreateFolders(true);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);
        $uploader->setFilenamesCaseSensitivity(true);
        $uploader->setAllowedExtensions(['csv']);
        $uploader->setAllowRenameFiles(true);
        $result = $uploader->save($directoryRead->getAbsolutePath('import'), $fileName);
        if ($result === false) {
            throw new LocalizedException(new Phrase('The uploaded file could not be saved.'));
        }

        return $directoryRead->getAbsolutePath($result['path'] . '/' . $result['file']);
    }
}
