<?php

use yii\db\Migration;

/**
 * Class m190501_123524_create_table_conversation_messages
 */
class m190501_123524_create_table_conversation_messages extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conversation_messages', [
            'id'                =>  $this->primaryKey(),
            'id_conversation'   =>  $this->integer()->notNull(),
            'id_owner'          =>  $this->integer()->notNull(),
            'date'              =>  $this->integer()->notNull(),
            'text'              =>  $this->text(),
            'remove'            =>  $this->text()->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190501_123524_create_table_conversation_messages cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190501_123524_create_table_conversation_messages cannot be reverted.\n";

        return false;
    }
    */
}
