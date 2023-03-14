<?php

namespace app\controllers;

use app\forms\FormUsers;
use KebaCorp\VaultSecret\VaultSecret;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use TelegramBot\Api\Types\Update;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
     * @throws NotFoundHttpException
     */
    public function actionHook()
    {
        if (Yii::$app->getRequest()->isPost) {
            try {
                $token = VaultSecret::getSecret('TELEGRAM_TOKEN');
                $hook = new Client($token);
                $bot = new BotApi($token);

                $formUsers = new FormUsers();

                // Команда "start" - Регистрация в боте
                $hook->command('start', function ($message) use ($hook, $bot, $formUsers) {
                    $formUsers->setChat($message->getChat());
                    $chatId = $message->getChat()->getId();
                    $messageId = $message->getMessageId();
                    $bot->deleteMessage($chatId, $messageId);

                    if (!$formUsers->inBase($chatId)) {
                        $sendResult = $hook->sendMessage($chatId, $this->getMessage('MESSAGE_START_1'));
                        $messageId = $sendResult->getMessageId();

                        sleep(2);
                        $bot->editMessageText($chatId, $messageId, $this->getMessage('MESSAGE_START_2'));

                        sleep(2);
                        $bot->editMessageText($chatId, $messageId, $this->getMessage('MESSAGE_START_3'));

                        sleep(2);
                        $formUsers->save();
                        $bot->editMessageText($chatId, $messageId, $formUsers->getMessage());
                    } else {
                        $bot->deleteMessage($chatId, $messageId);
                    }


                });

                // Команда "active" - Изменить глобальную активность получения уведомлений
                $hook->command('active', function ($message) use ($hook, $bot, $formUsers) {
                    $chatId = $message->getChat()->getId();
                    $messageId = $message->getMessageId();
                    $bot->deleteMessage($chatId, $messageId);

                    if ($formUsers->inBase($chatId)) {
                        $sendResult = $hook->sendMessage($chatId, $this->getMessage('MESSAGE_ACTIVE_1'));
                        $messageId = $sendResult->getMessageId();

                        sleep(2);
                        $isActive = $formUsers->getActive($chatId);
                        $bot->editMessageText($chatId, $messageId, $this->getMessage('MESSAGE_ACTIVE_2_' . $isActive));

                        sleep(2);
                        $bot->editMessageText($chatId, $messageId, $this->getMessage('MESSAGE_ACTIVE_3'));

                        sleep(2);
                        $bot->editMessageText($chatId, $messageId, $this->getMessage('MESSAGE_ACTIVE_4'));

                        sleep(2);
                        $formUsers->updateActive($chatId);
                        $bot->editMessageText($chatId, $messageId, $formUsers->getMessage());
                    } else {
                        $bot->sendMessage($chatId, $this->getMessage('MESSAGE_ERROR'));
                    }
                });

                // Команда "info" - Получить информацию о том, сколько раз участвовал в перекурах
                $hook->command('info', function ($message) use ($hook, $bot) {
                    $chatId = $message->getChat()->getId();
                    $messageId = $message->getMessageId();
                    $bot->deleteMessage($chatId, $messageId);
                });

                // Команда "smoke" - Предложить пойти покурить всем активным пользоватеклям
                $hook->command('smoke', function ($message) use ($hook, $bot) {
                    $chatId = $message->getChat()->getId();
                    $messageId = $message->getMessageId();
                    $bot->deleteMessage($chatId, $messageId);
                });

                $hook->run();
            } catch (Exception $e) {
                $e->getMessage();
            }
        } else {
            throw new NotFoundHttpException('Данной страницы не существует');
        }
    }

    /**
     * @param $message
     * @param array $context
     * @return string
     */
    protected function getMessage($message, array $context = [])
    {
        return Yii::t('telegram', $message, $context);
    }

}