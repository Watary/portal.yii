<?php

use yii\db\Migration;

/**
 * Class m190606_104141_update_table_conversation_participant_conversation_participan_add_exclude
 */
class m190606_104141_update_table_conversation_participant_conversation_participan_add_exclude extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('conversation_participant', 'exclude', $this->integer()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190606_104141_update_table_conversation_participant_conversation_participan_add_exclude cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190606_104141_update_table_conversation_participant_conversation_participan_add_exclude cannot be reverted.\n";

        return false;
    }
    */
}
