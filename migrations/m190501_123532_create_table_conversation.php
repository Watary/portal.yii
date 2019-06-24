<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m190501_123532_create_table_conversation
 */
class m190501_123532_create_table_conversation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conversation', [
            'id'                =>  $this->primaryKey(),
            'id_owner'          =>  $this->integer(),
            'dialog'            =>  $this->boolean()->defaultValue(false),
            'date_update'       =>  Schema::TYPE_INTEGER . ' NOT NULL',
            'date_create'       =>  Schema::TYPE_INTEGER . ' NOT NULL',
            'remove'            =>  $this->text()->notNull()->defaultValue(''),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190501_123532_create_table_conversation cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190501_123532_create_table_conversation cannot be reverted.\n";

        return false;
    }
    */
}
