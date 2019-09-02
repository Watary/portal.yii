<?php

use yii\db\Migration;

/**
 * Class m190828_151750_create_tables_for_gallery
 */
class m190828_151750_create_tables_for_gallery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Таблиця з галереями
        $this->createTable('gallery_galleries', [
            'id'                    =>  $this->primaryKey(),
            'title'                 =>  $this->string(255)->defaultValue(NULL),
            'description'           =>  $this->text()->defaultValue(NULL),
            'alias'                 =>  $this->string(255)->notNull(),
            'setting'               =>  $this->text()->defaultValue(NULL),
            'id_owner'              =>  $this->integer(11)->notNull(),
            'id_deleted'            =>  $this->integer(11)->defaultValue(NULL),
            'deleted_at'            =>  $this->integer(11)->defaultValue(NULL),
            'created_at'            =>  $this->integer(11)->notNull(),
            'updated_at'            =>  $this->integer(11)->defaultValue(NULL),
        ]);

        // Таблиця з зображеннями
        $this->createTable('gallery_images', [
            'id'                    =>  $this->primaryKey(),
            'gallery'               =>  $this->integer(11)->notNull(),
            'title'                 =>  $this->string(255)->defaultValue(NULL),
            'description'           =>  $this->text()->defaultValue(NULL),
            'path'                  =>  $this->text()->notNull(),
            'path_thumbnail'        =>  $this->text()->defaultValue(NULL),
            'id_deleted'            =>  $this->integer(11)->defaultValue(NULL),
            'deleted_at'            =>  $this->integer(11)->defaultValue(NULL),
            'created_at'            =>  $this->integer(11)->notNull(),
            'updated_at'            =>  $this->integer(11)->defaultValue(NULL),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('gallery_galleries');
        $this->dropTable('gallery_images');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190828_151750_create_tables_for_gallery cannot be reverted.\n";

        return false;
    }
    */
}
