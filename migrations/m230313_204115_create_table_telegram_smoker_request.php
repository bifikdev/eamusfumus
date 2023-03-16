<?php

use yii\db\Migration;

class m230313_204115_create_table_telegram_smoker_request extends Migration
{

    public $tableName = '{{%telegramSmokeRequest%}}';
    public $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->comment('ID записи'),
            'idChat' => $this->bigInteger()->null()->comment('ID чата (автор)'),

            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата и время в timestamp'),
        ], $this->tableOptions);

        $this->addForeignKey('fk-users-chat', $this->tableName, 'idChat', 'telegramUsers', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-users-chat', $this->tableName);

        $this->dropTable($this->tableName);
    }

}
