<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegramUsers".
 *
 * @property int $id ID чата бота с пользователем
 * @property int $idMessage ID первого сообщения, в котором будет хранится информация
 * @property string|null $lastName Фамилия пользователя telegram
 * @property string|null $firstName Имя пользователя telegram
 * @property string|null $username Ник пользователя telegram
 * @property int|null $isActive Активность (получать или нет уведомления
 * @property int|null $isReady Готовность (получать предложения покурить или нет
 */
class TelegramUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegramUsers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idMessage'], 'required'],
            [['id', 'idMessage', 'isActive', 'isReady'], 'integer'],
            [['lastName', 'firstName', 'username'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'MODEL_TELEGRAM_USERS_ID'),
            'idMessage' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGE_ID'),
            'lastName' => Yii::t('models', 'MODEL_TELEGRAM_USERS_LAST_NAME'),
            'firstName' => Yii::t('models', 'MODEL_TELEGRAM_USERS_FIRST_NAME'),
            'username' => Yii::t('models', 'MODEL_TELEGRAM_USERS_USERNAME'),
            'isActive' => Yii::t('models', 'MODEL_TELEGRAM_USERS_IS_ACTIVE'),
            'isReady' => Yii::t('models', 'MODEL_TELEGRAM_USERS_IS_READY'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(TelegramMessages::class, ['idMessage' => 'idMessage']);
    }
}
