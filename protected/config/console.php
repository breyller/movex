<?php

return [
    'basePath' => dirname(__DIR__),
    'name' => 'MoveX Console',

    'components' => [
        'db' => require __DIR__ . '/database.php',
    ],
];