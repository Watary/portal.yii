<?php

use yii\db\Migration;

/**
 * Class m190325_085800_create_table_message
 */
class m190325_085800_create_table_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('messages', [
            'id'            =>  $this->primaryKey(),
            'id_user'       =>  $this->integer()->notNull(),
            'id_whom'       =>  $this->integer()->notNull(),
            'active'        =>  $this->boolean()->notNull()->defaultValue(false),
            'date'          =>  $this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('messages');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190325_085800_create_table_message cannot be reverted.\n";

        return false;
    }
    */
}
