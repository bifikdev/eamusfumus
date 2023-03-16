<?php

use KebaCorp\VaultSecret\VaultSecret;

return [
    'class' => \yii\db\Connection::class,
    'dsn' => sprintf('mysql:host=%s;dbname=%s',
        VaultSecret::getSecret('MYSQL_HOSTNAME', 'localhost'),
        VaultSecret::getSecret('MYSQL_BASENAME', 'database')
    ),
    'username' => VaultSecret::getSecret('MYSQL_USERNAME', 'root'),
    'password' => VaultSecret::getSecret('MYSQL_PASSWORD', 'root'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
