<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegramMessages".
 *
 * @property int $id Уникальный ID
 * @property int|null $idSmokeRequest ID предложения
 * @property int $idMessage Уникальный ID сообщения
 * @property int $idChat Уникальный ID чата
 * @property string|null $type Тип сообщения (public / private
 * @property string|null $text Текст сообщения
 * @property int|null $date Дата и время в timestamp
 * @property string|null $entitiesType Тип сообщения (bot_command / пустое значение)
 *
 * @property TelegramUsers $idChat0
 * @property TelegramSmokeRequest $idSmokeRequest0
 */
class TelegramMessages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegramMessages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idSmokeRequest', 'idMessage', 'idChat', 'date'], 'integer'],
            [['idMessage', 'idChat'], 'required'],
            [['type', 'entitiesType'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 1500],
            [['idChat'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramUsers::class, 'targetAttribute' => ['idChat' => 'id']],
            [['idSmokeRequest'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramSmokeRequest::class, 'targetAttribute' => ['idSmokeRequest' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ID'),
            'idSmokeRequest' => Yii::t('models', 'MODEL_TELEGRAM_SMOKE_REQUEST_ID'),
            'idMessage' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ID_MESSAGE'),
            'idChat' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ID_CHAT'),
            'type' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_TYPE'),
            'text' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_TEXT'),
            'date' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_DATE'),
            'entitiesType' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ENTITIES_TYPE'),
        ];
    }

    /**
     * Gets query for [[IdChat0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(TelegramUsers::class, ['id' => 'idChat']);
    }

    /**
     * Gets query for [[IdSmokeRequest0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmokeRequest()
    {
        return $this->hasOne(TelegramSmokeRequest::class, ['id' => 'idSmokeRequest']);
    }
}
