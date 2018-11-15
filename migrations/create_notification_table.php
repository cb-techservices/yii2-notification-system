<?php
use yii\db\Migration;

class create_notification_table extends Migration
{
    const TABLE_NAME = '{{%notification}}';
    
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
        	'user_id' => $this->integer()->notNull(),
            'key' => $this->string()->notNull(),
            'key_id' => $this->integer(),
            'type' => $this->string()->notNull(),
            'read' => $this->boolean()->notNull(),
        	'flashed' => $this->boolean()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        	'updated_at' => $this->dateTime(),
        ]);
    }
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}