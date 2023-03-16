<?php

use KebaCorp\VaultSecret\VaultSecret;

return [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'language' => 'ru_RU',
    'name' => VaultSecret::getSecret('YII_APP_NAME', ''),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'gii' => [
            'class' => \yii\gii\Module::class,
            'allowedIPs' => VaultSecret::getSecret('YII_ALLOW_IP', ['127.0.0.1'])
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => VaultSecret::getSecret('YII_COOKIE'),
            'baseUrl' => VaultSecret::getSecret('YII_APP_BASE_URL', ''),
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class
        ],
        'user' => [
            'class' => \app\models\User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'i18n' => [
            'translations' => require_once __DIR__ . '/config/_i18n.php',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => require_once __DIR__ . '/config/_logs.php',
        ],
        'db' => require_once __DIR__ . '/config/_db.php',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require_once __DIR__ . '/config/_urls.php',
        ],
    ],
    'params' => require_once __DIR__ . '/config/_params.php',
];
