<?php

return [
    'class' => 'CDbConnection',
    'connectionString' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s',
        getenv('DB_HOST'),
        getenv('DB_PORT'),
        getenv('DB_DATABASE')
    ),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8mb4'
];
