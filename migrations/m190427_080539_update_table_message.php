<?php

use yii\db\Migration;

/**
 * Class m190427_080539_update_table_message
 */
class m190427_080539_update_table_message extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('message', 'read', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190427_080539_update_table_message cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190427_080539_update_table_message cannot be reverted.\n";

        return false;
    }
    */
}
