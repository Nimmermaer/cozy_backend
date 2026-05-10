<?php

declare(strict_types=1);

use Mblunck\CozyBackend\Enum\Doktype;
use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, $extensionKey): void {

    foreach (Doktype::cases() as $key) {
        $GLOBALS['TCA']['pages']['types'][$key->value]['allowedRecordTypes'] = ['*'];
        $GLOBALS['TCA']['pages']['ctrl']['typeicon_classes'][$key->value] = 'icon-doktype-' . strtolower($key->name);
        $GLOBALS['TCA']['pages']['types'][$key->value] = $GLOBALS['TCA']['pages']['types'][1];

        ExtensionManagementUtility::addTcaSelectItem(
            'pages',
            'doktype',
            new SelectItem(
                type: 'select',
                label: 'LLL:EXT:cozy_backend/Resources/Private/Language/locallang.xlf:page_type.' . strtolower($key->name),
                value: $key->value,
                icon: 'icon-doktype-' . strtolower($key->name),
                group: 'special',
                description: 'Custom Doktype',
            )
        );
    }

}, 'pages', 'cozy_backend');
