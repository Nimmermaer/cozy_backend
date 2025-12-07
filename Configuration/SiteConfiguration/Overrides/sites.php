<?php

$GLOBALS['SiteConfiguration']['site']['columns']['webmanifest_description'] = [
    'label' => 'Manifest description',
    'config' => [
        'type' => 'input',
    ],
];

$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] = str_replace(
    ', routes',
    ', routes,--div--;Extended,webmanifest_description, ',
    $GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'],
);
