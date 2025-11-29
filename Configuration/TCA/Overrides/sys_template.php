<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function ($table, $extensionKey): void {
    ExtensionManagementUtility::addStaticFile($extensionKey, 'Configuration/TypoScript', 'cozy_backend');
}, 'sys_template', 'cozy_backend');
