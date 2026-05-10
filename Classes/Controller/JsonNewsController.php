<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Controller;

use Mblunck\CozyBackend\Domain\Model\News;
use Mblunck\CozyBackend\Domain\Repository\NewsRepository;
use Mblunck\CozyBackend\Event\JsonNewsEvent;
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
        /** @var News $newsItem */
        foreach ($news as $newsItem) {
            $json[] = $newsItem->getArray();
        }
        $event = $this->eventDispatcher->dispatch(
            new JsonNewsEvent($json)
        );
        return $this->jsonResponse(json_encode($event->getNews()));
    }
}
