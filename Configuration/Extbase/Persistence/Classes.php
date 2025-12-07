<?php

declare(strict_types=1);


use Mblunck\CozyBackend\Domain\Model\News;

return [
    News::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'title' => [
                'fieldName' => 'header',
            ],
            'id' => [
                'fieldName' => 'uid',
            ],
            'teaser' => [
                'fieldName' => 'rowDescription',
            ],
            'content' => [
                'fieldName' => 'bodytext',
            ],
            'date' => [
                'fieldName' => 'crdate',
            ],
        ],

    ],
];