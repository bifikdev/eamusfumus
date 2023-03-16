<?php

namespace app\forms;

use app\models\TelegramMessages;
use TelegramBot\Api\Types\Chat;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;
use yii\base\Model;

final class FormMessages extends Model
{

    /**
     * @var Message $message
     */
    protected $message;

    /**
     * @var Chat $chat
     */
    protected $chat;

    /**
     * @param Message $message
     */
    public function setMessage(Message $message): void
    {
        $this->message = $message;
        $this->setChat($message->getChat());
    }

    /**
     * @param Chat $chat
     */
    public function setChat(Chat $chat): void
    {
        $this->chat = $chat;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $message = $this->getMessage();
        $chat = $this->getChat();

        $model = new TelegramMessages();
        $model->id = time();

        $model->idMessage = $message->getMessageId();
        $model->text = $message->getText();
        $model->date = $message->getDate();

        $model->idChat = $chat->getId();
        $model->type = $chat->getType();

        //TODO: Добавить парсинг entities
        return ($model->validate() && $model->save());
    }

    /**
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @return Message
     */
    protected function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return Update
     */
    protected function getUpdate(): Update
    {
        return $this->update;
    }
}