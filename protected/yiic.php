<?php

defined('YII_DEBUG') or define(
    'YII_DEBUG',
    filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)
);

$yiic = dirname(__DIR__) . '/vendor/yiisoft/yii/framework/yiic.php';
$config = __DIR__ . '/config/console.php';

require_once $yiic;