<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(static function (): void {
    ExtensionManagementUtility::addStaticFile('cozy_backend', 'Configuration/TypoScript', 'cozy_backend');
    ExtensionManagementUtility::addPageTSConfig('@import "EXT:cozy_backend/Configuration/TsConfig/Page/All.tsconfig"');
});
