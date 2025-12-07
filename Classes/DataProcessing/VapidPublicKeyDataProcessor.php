<?php

namespace Mblunck\CozyBackend\DataProcessing;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;


class VapidPublicKeyDataProcessor implements DataProcessorInterface
{

    public function __construct(
        protected PageRenderer $pageRenderer,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
       if(ArrayUtility::isValidPath($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'], 'cozy_backend/vapidPublicKey')) {
          $this->pageRenderer->addBodyContent( "<div id='VapidPublicKey' data-key='" .$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['cozy_backend']['vapidPublicKey'] . "' />");
       }
       return $processedData;
    }
}