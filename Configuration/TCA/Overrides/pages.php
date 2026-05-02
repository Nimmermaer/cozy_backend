<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, $extensionKey): void {

    foreach (\Mblunck\CozyBackend\Enum\Doktype::cases() as $key) {
        $GLOBALS['TCA']['pages']['types'][$key->value]['allowedRecordTypes'] = ['*'];
        $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$key->value] = 'icon-doktype-' . strtolower($key->name);
        $GLOBALS['TCA']['pages']['types'][$key->value] = $GLOBALS['TCA']['pages']['types'][1];

        ExtensionManagementUtility::addTcaSelectItem(
            'pages',
            'doktype',
            new SelectItem(
                'select',
                'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:page_type.'. strtolower($key->name),
                $key->value,
                'icon-doktype-' . strtolower($key->name),
                'special',
                'Custom Doktype',
            )
        );
    }

}, 'pages', 'cozy_backend');
