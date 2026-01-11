<?php

declare(strict_types=1);

use Mblunck\CozyBackend\Middleware\PushNotificationMiddleware;

return [
    'frontend' => [
        'middleware-identifier' => [
            'target' => PushNotificationMiddleware::class,
            'before' => [
                'typo3/cms-redirects/redirecthandler',
            ],
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
    ],
];
