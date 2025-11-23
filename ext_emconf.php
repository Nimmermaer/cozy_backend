<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'Cozy Backend',
    'description' => 'Accompanying extension for the TYPO3 housekeeping presentation',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Mblunck\\CozyBackend\\' => 'Classes/',
        ],
    ],
];
