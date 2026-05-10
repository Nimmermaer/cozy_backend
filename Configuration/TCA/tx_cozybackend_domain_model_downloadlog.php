<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'Download log',
        'label' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'typeicon_classes' => [
            'default' => 'content-message-dots',
        ],
    ],
    'types' => [
        [
            'showitem' => '
                --div--;core.form.tabs:general,
                    referrer,crdate,fe_user,description,
                --div--;core.form.tabs:access,
                    hidden,--palette--;;access,
                    fe_groups,
                --div--;core.form.tabs:extended,
            ',
        ],
    ],
    'palettes' => [
        'access' => [
            'showitem' => 'starttime;core.db.general:starttime,endtime;core.db.general:endtime',
        ],
        'language' => [
            'showitem' => 'sys_language_uid, l10n_parent',
        ],
    ],
    'columns' => [
        'referrer' => [
            'exclude' => true,
            'label' => 'Referrer',
            'config' => [
                'type' => 'input',
            ],
        ],
        'crdate' => [
            'exclude' => true,
            'label' => 'Creation Date',
            'config' => [
                'readOnly' => true,
                'type' => 'datetime',
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'Description',
            'config' => [
                'readOnly' => true,
                'type' => 'text',
            ],
        ],
        'fe_user' => [
            'exclude' => true,
            'label' => 'Frontend User',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
                'size' => 1,
                'maxitems' => 1,

            ],
        ],
    ],
];
