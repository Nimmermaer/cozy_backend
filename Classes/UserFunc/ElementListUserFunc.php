<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\UserFunc;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\Exception\AspectPropertyNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ElementListUserFunc extends UserFuncHelper
{
    public function __construct(
        private readonly ViewFactoryInterface $viewFactory,
        private readonly ConnectionPool $connectionPool,
        private readonly Context $context
    ) {
        parent::__construct($this->viewFactory);
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws Exception
     */
    public function listElements(string $content, array $conf, ServerRequestInterface $request): string
    {
        /** @var ContentObjectRenderer $currentContentObject */
        $currentContentObject = $request->getAttribute('currentContentObject');
        $view = $this->getView($request);
        $settings = $this->getSettings($currentContentObject->data['pi_flexform']);
        $view->assign('data', $currentContentObject->data);
        $view->assign('elements', $this->getElements($settings));
        return $view->render($conf['templateName']);
    }

    /**
     * @throws AspectNotFoundException
     * @throws AspectPropertyNotFoundException
     * @throws Exception
     */
    private function getElements(array $settings = []): array
    {
        $connectionPool = $this->connectionPool->getConnectionForTable('tt_content');
        $identifiers = [
            'deleted' => 0,
            'hidden' => 0,
        ];

        $languageAspect = $this->context->getAspect('language');
        if ($languageAspect > 0) {
            $identifiers += [
                'sys_language_uid' => $languageAspect->get('id'),
            ];
        }
        if (array_key_exists('singlePid', $settings) && $settings['singlePid'] > 0) {
            $identifiers += [
                'pid' => $settings['singlePid'],
            ];
        }

        $sorting = [];
        if (array_key_exists('sorting', $settings)) {
            $sorting = [
                $settings['sorting'] => QueryInterface::ORDER_ASCENDING,
            ];
        }
        return $connectionPool->select(
            ['*'],
            'tt_content',
            $identifiers,
            ['CType'],
            $sorting,
        )->fetchAllAssociative();
    }
}
