<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegramMessages".
 *
 * @property int $idUpdate Уникальный ID записи
 * @property int $idMessage Уникальный ID сообщения
 * @property int $idChat Уникальный ID чата
 * @property string|null $type Тип сообщения (public / private
 * @property string|null $text Текст сообщения
 * @property int|null $date Дата и время в timestamp
 * @property string|null $entitiesType Тип сообщения (bot_command / пустое значение)
 *
 * @property TelegramUsers $idChat0
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
            [['idUpdate', 'idMessage', 'idChat'], 'required'],
            [['idUpdate', 'idMessage', 'idChat', 'date'], 'integer'],
            [['type', 'entitiesType'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 1500],
            [['idUpdate'], 'unique'],
            [['idChat'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramUsers::class, 'targetAttribute' => ['idChat' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idUpdate' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ID_UPDATE'),
            'idMessage' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ID_MESSAGE'),
            'idChat' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ID_CHAT'),
            'type' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_TYPE'),
            'text' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_TEXT'),
            'date' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_DATE'),
            'entitiesType' => Yii::t('models', 'MODEL_TELEGRAM_MESSAGES_ENTITIES_TYPE'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(TelegramUsers::class, ['id' => 'idChat']);
    }
}
