<?php

return [
    'basePath' => dirname(__DIR__),
    'name' => 'MoveX',
    'components' => [
        'db' => require __DIR__ . '/database.php',

        'urlManager' => [
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => [
                'health' => 'site/health',
            ],
        ],

        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ],
            ],
        ],
    ],
];