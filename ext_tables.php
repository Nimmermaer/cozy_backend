<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

// Backend Customization
ExtensionManagementUtility::addUserTSConfig("@import 'EXT:cozy_backend/Configuration/TsConfig/User/options.tsconfig'");
