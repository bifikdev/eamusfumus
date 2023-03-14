<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegramUsers".
 *
 * @property int $id ID чата бота с пользователем
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
            [['id'], 'required'],
            [['id', 'isActive', 'isReady'], 'integer'],
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
            'lastName' => Yii::t('models', 'MODEL_TELEGRAM_USERS_LAST_NAME'),
            'firstName' => Yii::t('models', 'MODEL_TELEGRAM_USERS_FIRST_NAME'),
            'username' => Yii::t('models', 'MODEL_TELEGRAM_USERS_USERNAME'),
            'isActive' => Yii::t('models', 'MODEL_TELEGRAM_USERS_IS_ACTIVE'),
            'isReady' => Yii::t('models', 'MODEL_TELEGRAM_USERS_IS_READY'),
        ];
    }
}
