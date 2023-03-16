<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegramSmokeRequest".
 *
 * @property int $id ID записи
 * @property int|null $idChat ID чата (автор)
 * @property string $date Дата и время в timestamp
 *
 * @property TelegramUsers $idChat0
 */
class TelegramSmokeRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegramSmokeRequest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idChat'], 'integer'],
            [['date'], 'safe'],
            [['idChat'], 'exist', 'skipOnError' => true, 'targetClass' => TelegramUsers::class, 'targetAttribute' => ['idChat' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'MODELS_TELEGRAM_SMOKE_REQUEST_ID'),
            'idChat' => Yii::t('models', 'MODELS_TELEGRAM_SMOKE_REQUEST_ID_CHAT'),
            'date' => Yii::t('models', 'MODELS_TELEGRAM_SMOKE_REQUEST_DATE'),
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
}
