<?php

namespace app\controllers;

use app\forms\FormMessages;
use app\forms\FormRequest;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use app\forms\FormUsers;
use app\models\TelegramUsers;
use KebaCorp\VaultSecret\VaultSecret;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Update;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

final class TelegramController extends Controller
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
        $request = Yii::$app->getRequest();
        if ($request->isPost
            //&& in_array($request->getUserIP(), VaultSecret::getSecret("TELEGRAM_HOOK_IP", []))
        ) {

            Yii::info(Json::encode(Json::decode(file_get_contents('php://input'))), 'hook');

            try {
                $token = VaultSecret::getSecret('TELEGRAM_TOKEN');
                $hook = new Client($token);
                $bot = new BotApi($token);

                $users = new FormUsers();
                $smoke = new FormRequest();
                $formMessages = new FormMessages();

                // Команда "start" - Регистрация в боте
                $hook->command('start', function ($message) use ($hook, $bot, $users) {
                    $id = $message->getChat()->getId();

                    if (!$users->inBase($id)) {
                        $newMessage = $hook->sendMessage($id, $this->getMessage('MESSAGE_START'));
                        $users->setMessage($newMessage);
                        $users->register();
                    }

                    $model = $users->getUser($id);
                    $bot->editMessageText($model->id, $model->idMessage, $this->getTemplate($model));
                    $bot->deleteMessage($id, $message->getMessageId());
                });

                // Команда "active" - Изменить глобальную активность получения уведомлений
                $hook->command('active', function ($message) use ($hook, $bot, $users) {
                    $id = $message->getChat()->getId();

                    if ($users->inBase($id)) {
                        $users->changeActive($id);
                        $model = $users->getUser($id);
                        $bot->editMessageText($id, $model->idMessage, $this->getTemplate($model));
                    }

                    $bot->deleteMessage($id, $message->getMessageId());
                });

                // Команда "delete" - Удалить свою запись из бота
                $hook->command('delete', function ($message) use ($hook, $bot, $users) {
                    $id = $message->getChat()->getId();
                    if ($users->inBase($id)) {
                        $users->deleteUser($id);
                    }
                    $bot->deleteMessage($id, $message->getMessageId());
                });

                // Команда "info" - Получить информацию о том, сколько раз участвовал в перекурах
                $hook->command('info', function ($message) use ($hook, $bot, $users) {
                    $id = $message->getChat()->getId();
                    if ($users->inBase($id)) {
                        $model = $users->getUser($id);
                        $bot->editMessageText($id, $model->idMessage, $this->getTemplate($model));
                    }
                    $bot->deleteMessage($id, $message->getMessageId());
                });

                // Команда "smoke" - Предложить пойти покурить всем активным пользоватеклям
                $hook->command('smoke', function ($message) use ($hook, $bot, $users, $smoke, $formMessages) {
                    $id = $message->getChat()->getId();
                    if ($users->inBase($id)) {
                        $smoke->setChat($message->getChat());
                        $smokeId = $smoke->save();
                        $newMessage = $hook->sendMessage($id, "Отправка уведомлений: \n");
                        $waitIdMessage = $newMessage->getMessageId();
                        $formMessages->setMessage($newMessage);
                        $formMessages->save($smokeId);
                        $activeUsers = $users->getUsers($id);
                        $waitUsers = [];
                        $waitUsers[] = "Отправка уведомлений";
                        foreach ($activeUsers as $i => $smoker) {
                            $buttons = [
                                self::getText(['icon' => self::UNICODE_YES, 'text' => 'Согласиться']),
                                self::getText(['icon' => self::UNICODE_NO, 'text' => 'Отказаться']),
                                self::getText(['icon' => self::UNICODE_CLOCK, 'text' => 'Позже']),
                            ];
                            $keyboard = new ReplyKeyboardMarkup([$buttons], true, true);

                            $smokeMessage = $hook->sendMessage($smoker->id, $message->getChat()->getUsername() . " предлагает покурить.", null, false, null, $keyboard);
                            $formMessages->setMessage($smokeMessage);
                            $formMessages->save($smokeId);
                            $waitUsers[] = "- " . self::UNICODE_WAIT . " " . $smoker->username;
                            $bot->editMessageText($id, $waitIdMessage, implode("\n", $waitUsers));
                        }

                        $waitUsers[0] = "Ожидаем ответа";
                        $bot->editMessageText($id, $waitIdMessage, implode("\n", $waitUsers));
                    }
                    $bot->deleteMessage($id, $message->getMessageId());
                });

                // Команда "new" - Если требуется создать новое сообщение для автообновлений
                $hook->command('new', function ($message) use ($hook, $bot, $users) {
                    $id = $message->getChat()->getId();
                    if ($users->inBase($id)) {
                        $newMessage = $hook->sendMessage($id, 'Loading...');
                        $users->changeMessageId($id, $newMessage->getMessageId());
                        $model = $users->getUser($id);
                        $bot->editMessageText($id, $model->idMessage, $this->getTemplate($model));
                    }

                    $bot->deleteMessage($id, $message->getMessageId());
                });

                // Пришли сообщение в бота
                $hook->on(function (Update $update) use ($hook, $bot, $users, $smoke,  $formMessages) {
                    $message = $update->getMessage();
                    $id = $message->getChat()->getId();
                    $text = $message->getText();

                    if ($users->inBase($id)) {



                        switch ($text) {
                            case self::getText(['icon' => self::UNICODE_YES, 'text' => 'Согласиться']):

                                break;

                            case self::getText(['icon' => self::UNICODE_NO, 'text' => 'Отказаться']):

                                break;

                            case self::getText(['icon' => self::UNICODE_CLOCK, 'text' => 'Позже']):

                                break;
                        }
                    }

                    $bot->deleteMessage($id, $message->getMessageId());
                }, function () {
                    return true;
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
     * @param string $template
     * @param array $context
     * @return string
     */
    protected static function getMessage(string $template, array $context = []): string
    {
        return Yii::t('telegram', $template, $context);
    }

    /**
     * @param TelegramUsers $model
     * @return string
     */
    protected function getTemplate(TelegramUsers $model): string
    {
        //https://apps.timwhitlock.info/emoji/tables/unicode
        return self::getMessage('MESSAGE_TEMPLATE', [
            'username' => $model->username,
            'active' => ($model->isActive) ? self::UNICODE_ON : self::UNICODE_OFF,
            'ready' => ($model->isReady) ? self::UNICODE_OFFICE : self::UNICODE_HOME,
            'smokeRequestAll' => FormRequest::getCountAll(),
            'smokeRequestWithMe' => FormRequest::getCount($model->id),
            'lastSmokeRequest' => FormRequest::getLast(),

            'iconSmoke' => self::UNICODE_SMOKE,
            'iconTimer' => self::UNICODE_TIMER,
            'iconHome' => self::UNICODE_HOME,
            'iconOffice' => self::UNICODE_OFFICE
        ]);
    }

    /**
     * @param array $context
     * @return string
     */
    protected function getText(array $context): string
    {
        return Yii::t('telegram', 'MESSAGE_BUTTON', $context);
    }

    const UNICODE_ON = "\u{2705}";
    const UNICODE_OFF = "\u{274E}";

    const UNICODE_TIMER = "\u{23F3}";
    const UNICODE_CLOCK = "\u{23F0}";
    const UNICODE_SMOKE = "\u{1F6AC}";

    const UNICODE_HOME = "\u{1F3E0}";
    const UNICODE_OFFICE = "\u{1F3E2}";

    const UNICODE_YES = "\u{2705}";
    const UNICODE_NO = "\u{274E}";
    const UNICODE_WAIT = "\u{23F3}";

    const UNICODE_ = "";

}