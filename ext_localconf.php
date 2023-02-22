<?php

declare(strict_types=1);


defined('TYPO3') || die();

// Backend Login Customization
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['backend'] = [
    'backendFavicon' => 'EXT:cozy_backend/Resources/Public/Backend/Images/favicon.ico',
    'backendLogo' => 'EXT:cozy_backend/Resources/Public/Backend/Images/icon.png',
    'loginBackgroundImage' => 'EXT:cozy_backend/Resources/Public/Backend/Images/backend.jpg',
    'loginFootnote' => date('Y') . '© by cozybackend',
    'loginHighlightColor' => '#275b64',
    'loginLogo' => 'EXT:cozy_backend/Resources/Public/Backend/Images/icon.png',
    'loginLogoAlt' => 'Cozy Backend logo',
];
