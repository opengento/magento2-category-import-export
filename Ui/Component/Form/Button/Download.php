<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CategoryImportExport\Ui\Component\Form\Button;

use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Opengento\CategoryImportExport\Model\Session\DownloadContext;

class Download implements ButtonProviderInterface
{
    public function __construct(
        private DownloadContext $downloadContext,
        private UrlInterface $urlBuilder
    ) {}

    public function getButtonData(): array
    {
        return $this->downloadContext->getFile()
            ? [
                'label' => new Phrase('Download'),
                'on_click' => sprintf("location.href = '%s';", $this->urlBuilder->getUrl('*/*/categoryDownload')),
                'class' => 'download primary'
            ]
            : [];
    }
}
