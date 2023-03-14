<?php

$class = \yii\i18n\PhpMessageSource::class;

return [
    'app' => [
        'class' => $class,
        'forceTranslation' => true,
    ],
    'telegram' => [
        'class' => $class,
        'forceTranslation' => true,
    ],
    'models' => [
        'class' => $class,
        'forceTranslation' => true,
    ],
    'forms' => [
        'class' => $class,
        'forceTranslation' => true,
    ],
    'migration' => [
        'class' => $class,
        'forceTranslation' => true,
    ],
];