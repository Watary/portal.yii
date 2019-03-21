<?php

use yii\db\Migration;

/**
 * Class m190321_084926_create_table_friends
 */
class m190321_084926_create_table_friends extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('friends', [
            'id'            =>  $this->primaryKey(),
            'id_user'       =>  $this->integer()->notNull(),
            'id_friend'     =>  $this->integer()->notNull(),
            'active'        =>  $this->boolean()->notNull()->defaultValue(false),
            'date'          =>  $this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('news');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190321_084926_create_table_friends cannot be reverted.\n";

        return false;
    }
    */
}
