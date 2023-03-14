<?php

namespace app\controllers;

use KebaCorp\VaultSecret\VaultSecret;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Update;
use yii\web\Controller;
use yii\web\Response;
use Yii;

class TelegramController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function actionHook()
    {
        try {
            $token = VaultSecret::getSecret('TELEGRAM_TOKEN');
            $bot = new Client(VaultSecret::getSecret($token));
            $tgBot = new BotApi(VaultSecret::getSecret($token));


            // Команда "start" - регистрация в боте и подпись на уведомления
            $bot->command('start', function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), 'Фиксирую информацию');
            });

            // Команда "active" - хочу или не хочу получать уведомления
            $bot->command('active', function ($message) use ($bot)  {
                $bot->sendMessage($message->getChat()->getId(), '');
            });

            // Отправить предложение покурить
            $bot->command('smoke', function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), '');
            });

            $bot->command('info', function ($message) use ($bot) {
                $bot->sendMessage($message->getChat()->getId(), '');
            });


            $bot->on(function (Update $update) use ($bot) {
                $message = $update->getMessage();
                $text = $message->getText();

//                if (preg_match('/Согласиться/', $text, $match)) {}
                //if (preg_match('/Отказаться/', $text, $match)) {}
                //if (preg_match('/Позже/', $text, $match)) {}
                //$bot->sendMessage($message->getChat()->getId(), $message->getText());

            }, function () {
                return true;
            });

            $bot->run();
        } catch (Exception $e) {
            // Сюда вообще не попадает бот, даже когда появляется какой-то Exception
            Yii::error($e->getMessage(), 'telegram');
        }
    }


}