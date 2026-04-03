<?php

declare(strict_types=1);

$EM_CONF['cozy_backend'] = [
    'title' => 'Cozy Backend',
    'description' => 'Accompanying extension for the TYPO3 housekeeping presentation',
    'constraints' => [
        'depends' => [
            'typo3' => '14.0.0-14.3.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Mblunck\\CozyBackend\\' => 'Classes/',
        ],
    ],
];
