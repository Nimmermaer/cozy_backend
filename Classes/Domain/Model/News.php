<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class News extends AbstractEntity
{
    protected int $id = 0 {
        get {
            // @extensionScannerIgnoreLine
            return $this->id;
        }
    }

    protected string $title = '' {
        get {
            return $this->title;
        }
    }

    protected string $content = '' {
        get {
            // @extensionScannerIgnoreLine
            return $this->content;
        }
    }

    protected int $date = 0 {
        get {
            return $this->date;
        }
    }

    protected string $teaser = '' {
        get {
            return $this->teaser;
        }
    }

    public function getArray(): array
    {
        return [
            'id' => $this->getUid(),
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'teaser' => $this->teaser,
        ];
    }
}
