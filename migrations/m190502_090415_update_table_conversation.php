<?php

use yii\db\Migration;

/**
 * Class m190502_090415_update_table_conversation
 */
class m190502_090415_update_table_conversation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190502_090415_update_table_conversation cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190502_090415_update_table_conversation cannot be reverted.\n";

        return false;
    }
    */
}
