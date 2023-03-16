<?php

use yii\db\Migration;

/**
 * Class m230313_194818_create_table_telegram_messages
 */
class m230313_194818_create_table_telegram_messages extends Migration
{

    public $tableName = '{{%telegramMessages%}}';
    public $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->bigInteger()->notNull()->comment('Уникальный ID'),
            // Данные из Message
            'idMessage' => $this->bigInteger()->notNull()->comment('Уникальный ID сообщения'),
            // Данные из Chat
            'idChat' => $this->bigInteger()->notNull()->comment('Уникальный ID чата'),
            'type' => $this->string(50)->null()->comment('Тип сообщения (public / private'),
            // Дополнительные данные
            'text' => $this->string(1500)->null()->comment('Текст сообщения'),
            'date' => $this->bigInteger()->null()->comment('Дата и время в timestamp'),
            'entitiesType' => $this->string(50)->null()->comment('Тип сообщения (bot_command / пустое значение)'),
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-id', $this->tableName, 'id');

        $this->addForeignKey('fk-chat', $this->tableName, 'idChat', 'telegramUsers', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-chat', $this->tableName);

        $this->dropPrimaryKey('pk-id');

        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230313_194818_create_table_telegram_messages cannot be reverted.\n";

        return false;
    }
    */
}
