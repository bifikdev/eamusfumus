<?php

namespace app\forms;

use app\models\TelegramUsers;
use yii\base\Model;
use Yii;

class FormUsers extends Model
{

    protected $chat;

    protected $message;

    public function save()
    {
        $chat = $this->getChat();
        $model = $this->getModel();

        // Если новый пользователь, то пишем сообщение
        if ($model->isNewRecord) {
            $model->id = $chat->getId();
            $model->firstName = $chat->getFirstName();
            $model->lastName = $chat->getLastName();
            $model->username = $chat->getUsername();
            if ($model->validate() && $model->save()) {
                $this->setMessage(Yii::t('forms', 'FORM_USERS_SUCCESS'));
                return true;
            }
            $this->setMessage(Yii::t('forms', 'ERROR_MESSAGE'));
        }
        // Если пользователь уже есть в БД - ничего не пишем
        return false;
    }

    /**
     * @param $chatId
     * @return bool
     */
    public function updateActive($chatId)
    {
        $model = $this->getModel($chatId);
        $model->isActive = ($model->isActive == 1) ? 0 : 1;
        if ($model->validate() && $model->save()) {
            $this->setMessage(Yii::t('forms', 'FORM_USERS_SUCCESS_ACTIVE_' . $model->isActive));
            return true;
        }
        $this->setMessage(Yii::t('forms', 'ERROR_MESSAGE'));
        return false;
    }

    /**
     * @param $chatId
     * @return int|null
     */
    public function getActive($chatId)
    {
        return $this->getModel($chatId)->isActive;
    }

    /**
     * @param int|null $id
     * @return TelegramUsers|null
     */
    protected function getModel(int $id = null)
    {
        return (is_null($id))
            ? new TelegramUsers()
            : TelegramUsers::findOne(['id' => $id]);
    }

    /**
     * @param $chatId
     * @return bool
     */
    public function inBase($chatId)
    {
        return TelegramUsers::find()->where(['id' => $chatId])->exists();
    }

    public function setChat($chat)
    {
        $this->chat = $chat;
    }

    protected function setMessage($message)
    {
        $this->message = $message;
    }

    protected function getChat()
    {
        return $this->chat;
    }

    public function getMessage()
    {
        return $this->message;
    }


}