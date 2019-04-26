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
        $this->createTable('message', [
            'id'            =>  $this->primaryKey(),
            'id_from'       =>  $this->integer()->notNull(),
            'id_to'       =>  $this->integer()->notNull(),
            'date'          =>  $this->integer()->notNull(),
            'text'       =>  $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('message');

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
