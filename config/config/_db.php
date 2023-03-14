<?php

use KebaCorp\VaultSecret\VaultSecret;

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:'
        .'host='.VaultSecret::getSecret('MYSQL_HOSTNAME')
        .';dbname='.VaultSecret::getSecret('MYSQL_BASENAME'),
    'username' => VaultSecret::getSecret('MYSQL_USERNAME'),
    'password' => VaultSecret::getSecret('MYSQL_PASSWORD'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
