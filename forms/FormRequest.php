<?php

namespace app\forms;

use app\models\TelegramSmokeRequest;
use TelegramBot\Api\Types\Chat;
use yii\base\Model;

final class FormRequest extends Model
{

    /**
     * @var int $Id
     */
    protected $id;

    /**
     * @var Chat $chat
     */
    protected $chat;

    /**
     * @param Chat $chat
     */
    public function setChat(Chat $chat): void
    {
        $this->chat = $chat;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function save(): int
    {
        $chat = $this->getChat();

        $model = new TelegramSmokeRequest();
        $model->idChat = $chat->getId();
        if ($model->validate() && $model->save()) {
            $this->setId($model->id);
            return $this->getId();
        }
    }

    /**
     * @param int $idChat
     * @return int
     */
    public static function getCount(int $idChat): int
    {
        return TelegramSmokeRequest::find()->where(['idChat' => $idChat])->count();
    }

    /**
     * @return int
     */
    public static function getCountAll(): int
    {
        return TelegramSmokeRequest::find()->count();
    }

    /**
     * @return false|string
     */
    public static function getLast()
    {
        $model = TelegramSmokeRequest::find()->orderBy('id DESC')->one();
        if ($model) {
            return date('d.m.Y H:i:s', strtotime($model->date));
        }
        return \Yii::t('telegram', 'SMOKE_DATE_NO');
    }

    /**
     * @return Chat
     */
    protected function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @param int $id
     */
    protected function setId(int $id): void
    {
        $this->id = $id;
    }

}