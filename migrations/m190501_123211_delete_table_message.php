<?php

use yii\db\Migration;

/**
 * Class m190501_123211_delete_table_message
 */
class m190501_123211_delete_table_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('message');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190501_123211_delete_table_message cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190501_123211_delete_table_message cannot be reverted.\n";

        return false;
    }
    */
}
