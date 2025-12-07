<?php

namespace Mblunck\CozyBackend\Controller;

use Mblunck\CozyBackend\Domain\Repository\NewsRepository;
use Mblunck\CozyBackend\Event\JsonNewsEvent;
use Mblunck\CozyBackend\Interface\SinglePageApplicationNewsInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class JsonNewsController extends ActionController
{

    public function __construct(
        protected NewsRepository $newsRepository,
    ) {
    }

    public function listAction(): ResponseInterface
    {
        $news = $this->newsRepository->findAll();
        $json = [];
        /** @var SinglePageApplicationNewsInterface $newsItem */
        foreach ($news as $newsItem) {
            $json[] = $newsItem->getArray();
        }
        $event = $this->eventDispatcher->dispatch(
            new JsonNewsEvent($json)
        );
        return $this->jsonResponse(json_encode($event->getNews()));
    }
}