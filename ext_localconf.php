<?php

declare(strict_types=1);

use Mblunck\CozyBackend\Controller\JsonNewsController;
use Mblunck\CozyBackend\Hooks\Datahandler;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

call_user_func(
    static function ($extensionKey): void {

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


        ExtensionUtility::configurePlugin(
            $extensionKey,
            'jsonNewsList',
            [
                JsonNewsController::class => 'list, show',
            ],
            pluginType: ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
            Datahandler::class;
    },
    'cozy_backend'
);
