<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Queue\Message;

use DateTimeImmutable;

class ExportMessage
{
    public function __construct(
        public int $feUserId,
        public string $referrer,
        public string $description,
        public DateTimeImmutable $tstamp,
        public DateTimeImmutable $crdate
    ) {
    }
}
