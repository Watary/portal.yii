<?php

use yii\db\Migration;

/**
 * Class m190316_185321_user_add_online
 */
class m190316_185321_user_add_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'online', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190316_185321_user_add_online cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190316_185321_user_add_online cannot be reverted.\n";

        return false;
    }
    */
}
