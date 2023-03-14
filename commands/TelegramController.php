<?php

namespace app\commands;


use KebaCorp\VaultSecret\VaultSecret;
use TelegramBot\Api\BotApi;
use yii\console\Controller;
use yii\console\ExitCode;

class TelegramController extends Controller
{

    /**
     * Установить webhook
     */
    public function actionSethook()
    {
        $url = 'https://eamusfumus.bifikdev.ru/telegram/hook';
        $client = new BotApi(VaultSecret::getSecret('TELEGRAM_TOKEN'));
        $result = $client->setWebhook($url);
        echo var_export($result, true) . PHP_EOL;
        return ExitCode::OK;
    }

    /**
     * Удалить webhook
     */
    public function actionDeletehook()
    {
        $client = new BotApi(VaultSecret::getSecret('TELEGRAM_TOKEN'));
        $result = $client->deleteWebhook();
        echo var_export($result, true) . PHP_EOL;
        return ExitCode::OK;
    }

    /**
     * Получить информацию по webhook
     */
    public function actionGethook()
    {
        $client = new BotApi(VaultSecret::getSecret('TELEGRAM_TOKEN'));
        $result = $client->getWebhookInfo();
        echo var_export($result, true) . PHP_EOL;
        return ExitCode::OK;
    }
}