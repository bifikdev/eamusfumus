<?php

$file = date('Ymd') . '.log';

return [
    [
        'class' => \yii\log\FileTarget::class,
        'levels' => ['error', 'warning'],
        'logVars' => [],
        'logFile' => '@runtime/logs/app/' . $file,
    ],
    [
        'categories' => ['telegram'],
        'class' => \app\logger\FileTargetCustom::class,
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
        'logFile' => '@runtime/logs/telegram/' . $file,
    ],
    [
        'categories' => ['hook'],
        'class' => \app\logger\FileTargetCustom::class,
        'levels' => ['error', 'warning', 'info'],
        'logVars' => [],
        'logFile' => '@runtime/logs/hook/' . $file,
    ],];