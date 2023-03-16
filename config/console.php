<?php

use KebaCorp\VaultSecret\VaultSecret;

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'language' => 'ru_RU',
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'modules' => [
        'gii' => [
            'class' => \yii\gii\Module::class,
            'allowedIPs' => VaultSecret::getSecret('YII_ALLOW_IP', ['127.0.0.1'])
        ],
    ],
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class
        ],
        'i18n' => [
            'translations' => require_once __DIR__ . '/config/_i18n.php',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => require_once  __DIR__ . '/config/_logs.php',
        ],
        'db' => require_once __DIR__ . '/config/_db.php',
    ],
    'params' => require __DIR__ . '/config/_params.php',
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];
