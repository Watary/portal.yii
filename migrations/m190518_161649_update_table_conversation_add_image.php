<?php

use yii\db\Migration;

/**
 * Class m190518_161649_update_table_conversation_add_image
 */
class m190518_161649_update_table_conversation_add_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('conversation', 'image', $this->string()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190518_161649_update_table_conversation_add_image cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190518_161649_update_table_conversation_add_image cannot be reverted.\n";

        return false;
    }
    */
}
