<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Event;

final class JsonNewsEvent
{
    public function __construct(
        private array $news
    ) {
    }

    public function getNews(): ?array
    {
        return $this->news;
    }

    public function setNews(?array $news): void
    {
        $this->news = $news;
    }

    public function addNews(array $news): void
    {

        $this->news = array_merge($this->news, $news);
    }
}