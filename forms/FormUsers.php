<?php

namespace app\forms;

use app\models\TelegramUsers;
use TelegramBot\Api\Types\Update;
use yii\base\Model;

final class FormUsers extends Model
{

    /**
     * @var Update $message
     */
    protected $message;


    /**
     * @param $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function register(): bool
    {
        $message = $this->message;
        $chat = $message->getChat();

        $model = new TelegramUsers();
        $model->id = $chat->getId();
        $model->idMessage = $message->getMessageId();
        $model->lastName = $chat->getLastName();
        $model->firstName = $chat->getFirstName();
        $model->username = $chat->getUsername();
        return ($model->validate() && $model->save());
    }

    /**
     * @param int $idChat
     * @return bool
     */
    public function inBase(int $idChat): bool
    {
        return TelegramUsers::find()
            ->where(['id' => $idChat])
            ->exists();
    }

    /**
     * Получить модель TelegramUsers по ID чата
     *
     * @param int $idChat
     * @return TelegramUsers
     */
    public function getUser(int $idChat): TelegramUsers
    {
        return TelegramUsers::findOne(['id' => $idChat]);
    }

    /**
     * Изменить маркер isActive пользователя
     *
     * @param int $id
     * @return bool
     */
    public function changeActive(int $id): bool
    {
        $model = $this->getModel($id);
        $model->isActive = ($model->isActive == 1) ? 0 : 1;
        return ($model->validate() && $model->save());
    }

    /**
     * Изменить маркер isReady пользователя
     *
     * @param int $id
     * @return bool
     */
    public function changeReady(int $id): bool
    {
        $model = $this->getModel($id);
        $model->isReady = ($model->isReady == 1) ? 0 : 1;
        return ($model->validate() && $model->save());
    }

    /**
     * Изменить id сообщение пользователя, которое служит для информации
     *
     * @param int $id
     * @param int $messageId
     * @return bool
     */
    public function changeMessageId(int $id, int $messageId): bool
    {
        $model = $this->getModel($id);
        $model->idMessage = $messageId;
        return ($model->validate() && $model->save());
    }


    /**
     * Обновить маркер isReady
     */
    public function startDay(): void
    {
        TelegramUsers::updateAll(['isReady' => 0]);
    }
}