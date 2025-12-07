<?php

namespace Mblunck\CozyBackend\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class News extends AbstractEntity
{

    protected int $id = 0;
    protected string $title = '';
    protected string $content = '';
    protected int $date = 0;
    protected string $teaser = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function getArray(): array
    {
        return [
            'id' => $this->getUid(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'date' => $this->getDate(),
            'teaser' => $this->getTeaser(),
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function getTeaser(): string
    {
        return $this->teaser;
    }

}