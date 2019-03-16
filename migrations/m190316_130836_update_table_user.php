<?php

use yii\db\Migration;

/**
 * Class m190316_130836_update_table_user
 */
class m190316_130836_update_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'first_name', $this->string());
        $this->addColumn('user', 'last_name', $this->string());
        $this->addColumn('user', 'avatar', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190316_130836_update_table_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190316_130836_update_table_user cannot be reverted.\n";

        return false;
    }
    */
}
