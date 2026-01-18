<?php

declare(strict_types=1);

namespace Mblunck\CozyBackend\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class TrackExport
{
    public function __construct(
        public string $description = 'PDF Export'
    ) {
    }
}
