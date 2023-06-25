<?php

declare(strict_types=1);


use TYPO3\CMS\Core\Core\Environment;

defined('TYPO3') || die();

if (Environment::getContext()->isDevelopment()) {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['cozy_backend'] =
        'EXT:cozy_backend/Resources/Public/Backend/Css/Dev/';
} else {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['cozy_backend'] =
        'EXT:cozy_backend/Resources/Public/Backend/Css/Live/';
}

// Backend Login Customization
//  system/additional.php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend'] = [
    'backendFavicon' => 'EXT:cozy_backend/Resources/Public/Backend/Images/favicon.ico',
    'backendLogo' => 'EXT:cozy_backend/Resources/Public/Backend/Images/icon.png',
    'loginBackgroundImage' => 'EXT:cozy_backend/Resources/Public/Backend/Images/backend.jpg',
    'loginFootnote' => date('Y') . '© by cozybackend',
    'loginHighlightColor' => '#275b64',
    'loginLogo' => 'EXT:cozy_backend/Resources/Public/Backend/Images/icon.png',
    'loginLogoAlt' => 'Cozy Backend logo',
];
