<?php

return [
    'ctrl' => [
        'title' => 'Push Subscription',
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
    ],
    'types' => [
        [
            'showitem' => 'hidden, sys_language_uid, l10n_diffsource, endpoint, p256dh, auth',
        ],
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_cozybackend_domain_model_subscription',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'searchWholePhrase' => true,
                        'addWhere' => 'AND tx_cozybackend_domain_model_subscription.sys_language_uid IN (0,-1)',
                    ],
                ],
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => 2145916800,
                ],
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'endpoint' => [
            'exclude' => true,
            'label' => 'Endpoint',
            'config' => [
                'type' => 'input',
            ],
        ],
        'p256dh' => [
            'exclude' => true,
            'label' => 'P256dh',
            'config' => [
                'type' => 'input',
            ],
        ],
        'auth' => [
            'exclude' => true,
            'label' => 'Auth',
            'config' => [
                'type' => 'input',
            ],
        ],
        'auth_token' => [
            'exclude' => true,
            'label' => 'Auth',
            'config' => [
                'type' => 'input',
            ],
        ],
        'content_encoding' => [
            'exclude' => true,
            'label' => 'Auth',
            'config' => [
                'type' => 'json',
            ],
        ],
        'public_key' => [
            'exclude' => true,
            'label' => 'Auth',
            'config' => [
                'type' => 'json',
            ],
        ],
        'json' => [
            'exclude' => true,
            'label' => 'Auth',
            'config' => [
                'type' => 'json',
            ],
        ],
    ],
];