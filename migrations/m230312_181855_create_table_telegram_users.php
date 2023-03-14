<?php

use yii\db\Migration;

class m230312_181855_create_table_telegram_users extends Migration
{

    public $tableName = '{{%telegramUsers%}}';
    public $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigInteger()->notNull()->comment('ID чата бота с пользователем'),
            'lastName' => $this->string(255)->null()->comment('Фамилия пользователя telegram'),
            'firstName' => $this->string(255)->null()->comment('Имя пользователя telegram'),
            'username' => $this->string(255)->null()->comment('Ник пользователя telegram'),
            'isActive' => $this->integer(1)->defaultValue(1)->comment('Активность (получать или нет уведомления'),
            'isReady' => $this->integer(1)->defaultValue(0)->comment('Готовность (получать предложения покурить или нет'),
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-id', $this->tableName, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('pk-id', $this->tableName);

        $this->dropTable($this->tableName);
    }

}
