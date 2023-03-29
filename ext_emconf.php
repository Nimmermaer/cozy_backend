<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'cozy_backend',
    'description' => 'Accompanying extension for the TYPO3 housekeeping presentation',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Mblunck\\CozyBackend\\' => 'Classes/',
        ],
    ],
];
