<?php
declare(strict_types=1);


defined('TYPO3') || die();
if(\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment()) {
    $GLOBALS['TBE_STYLES']['skins']['cozy_backend'] = [
        'name' => 'cozy_backend',
        'stylesheetDirectories' => [
            'css' => 'EXT:cozy_backend/Resources/Public/Backend/Css/'
        ]
    ];
}