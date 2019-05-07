<?php

use yii\db\Migration;

/**
 * Class m190501_124206_create_table_conversation_participant
 */
class m190501_124206_create_table_conversation_participant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conversation_participant', [
            'id'                    =>  $this->primaryKey(),
            'id_conversation'       =>  $this->integer()->notNull(),
            'id_user'               =>  $this->integer()->notNull(),
            'id_last_see'           =>  $this->integer()->notNull(),
            'date_entry'            =>  $this->integer()->notNull(),
            'date_exit'             =>  $this->integer()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190501_124206_create_table_conversation_participant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190501_124206_create_table_conversation_participant cannot be reverted.\n";

        return false;
    }
    */
}
