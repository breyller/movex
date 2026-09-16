<?php

defined('YII_DEBUG') or define(
    'YII_DEBUG',
    filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)
);

$yii = dirname(__DIR__) . '/vendor/yiisoft/yii/framework/yii.php';
$config = dirname(__DIR__) . '/protected/config/main.php';

require_once $yii;

Yii::createWebApplication($config)->run();