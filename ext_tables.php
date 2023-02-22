<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Core\Environment;


defined('TYPO3') || die();
if (Environment::getContext()->isDevelopment()) {
    $GLOBALS['TBE_STYLES']['skins']['cozy_backend'] = [
        'stylesheetDirectories' => [
            'css' => 'EXT:cozy_backend/Resources/Public/Backend/Css/Dev/',
        ],
    ];
} else {
    $GLOBALS['TBE_STYLES']['skins']['cozy_backend'] = [
        'stylesheetDirectories' => [
            'css' => 'EXT:cozy_backend/Resources/Public/Backend/Css/Live/',
        ],
    ];
}
