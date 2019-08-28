<?php

use yii\db\Migration;

/**
 * Class m190805_144852_create_tables_for_foum
 */
class m190805_144852_create_tables_for_foum extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(){
        // Таблиця з форумами
        $this->createTable('forum_forums', [
            'id'                    =>  $this->primaryKey(),
            'id_parent'             =>  $this->integer(11),
            'title'                 =>  $this->string(255)->notNull(),
            'description'           =>  $this->text(),
            'alias'                 =>  $this->string(255)->notNull(),
            'id_owner'              =>  $this->integer(11)->notNull(),
            'last_post'             =>  $this->integer(11)->defaultValue(NULL),
            'close'                 =>  $this->integer(1)->notNull()->defaultValue(0),
            'hot'                   =>  $this->integer(1)->notNull()->defaultValue(0),
            'count_forums'          =>  $this->integer(11)->notNull()->defaultValue(0),
            'count_topics'          =>  $this->integer(11)->notNull()->defaultValue(0),
            'count_posts'           =>  $this->integer(11)->notNull()->defaultValue(0),
            'id_deleted'            =>  $this->integer(11)->defaultValue(NULL),
            'deleted_at'            =>  $this->integer(11)->defaultValue(NULL),
            'created_at'            =>  $this->integer(11)->notNull(),
            'updated_at'            =>  $this->integer(11)->defaultValue(NULL),
        ]);

        // Таблиця з темами
        $this->createTable('forum_topics', [
            'id'                    =>  $this->primaryKey(),
            'id_parent'             =>  $this->integer(11)->notNull(),
            'title'                 =>  $this->string(255)->notNull(),
            'description'           =>  $this->text()->defaultValue(NULL),
            'alias'                 =>  $this->string(255)->notNull(),
            'id_owner'              =>  $this->integer(11)->notNull(),
            'last_post'             =>  $this->integer(11)->defaultValue(NULL),
            'close'                 =>  $this->integer(1)->notNull()->defaultValue(0), // 0 - відкрита | 1 - закрита | 2 - обмежений доступ
            'hot'                   =>  $this->integer(1)->notNull()->defaultValue(0),
            'count_posts'           =>  $this->integer(11)->notNull()->defaultValue(0),
            'id_deleted'            =>  $this->integer(11)->defaultValue(NULL),
            'deleted_at'            =>  $this->integer(11)->defaultValue(NULL),
            'created_at'            =>  $this->integer(11)->notNull(),
            'updated_at'            =>  $this->integer(11)->defaultValue(NULL),
        ]);

        // Таблиця з постами
        $this->createTable('forum_posts', [
            'id'                    =>  $this->primaryKey(),
            'text'                  =>  $this->text()->notNull(),
            'id_parent'             =>  $this->integer(11)->notNull(),
            'id_owner'              =>  $this->integer(11)->notNull(),
            'id_last_edit'          =>  $this->integer(11)->defaultValue(NULL),
            'id_deleted'            =>  $this->integer(11)->defaultValue(NULL),
            'deleted_at'            =>  $this->integer(11)->defaultValue(NULL),
            'created_at'            =>  $this->integer(11)->notNull(),
            'updated_at'            =>  $this->integer(11)->defaultValue(NULL),
        ]);

        // Таблиця з статистикою в форумові окремого користувача
        $this->createTable('forum_users', [
            'id'                    =>  $this->primaryKey(),
            'id_user'               =>  $this->integer(11)->notNull(),
            'count_create_forums'   =>  $this->integer(11)->notNull()->defaultValue(0),
            'count_create_topics'   =>  $this->integer(11)->notNull()->defaultValue(0),
            'count_create_posts'    =>  $this->integer(11)->notNull()->defaultValue(0),
            'id_last_post'          =>  $this->integer(11)->defaultValue(NULL),
        ]);

        // Таблиця для контроля за спостережання
        $this->createTable('forum_watch', [
            'id'                    =>  $this->primaryKey(),
            'id_user'               =>  $this->integer(11)->notNull(),
            'what_watch'            =>  $this->integer(1)->notNull(),
            'id_watch'              =>  $this->integer(11)->notNull(),
            'id_last_post'          =>  $this->integer(11)->notNull(),
        ]);

        // Таблиця для налаштувань форуму
        $this->createTable('forum_access', [
            'id'                    =>  $this->primaryKey(),
            'id_user'               =>  $this->integer(11)->notNull(),
            'what_access'           =>  $this->integer(1)->notNull(),
            'id_access'             =>  $this->integer(11)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('forum_forums');
        $this->dropTable('forum_topics');
        $this->dropTable('forum_posts');
        $this->dropTable('forum_users');
        $this->dropTable('forum_watch');
        $this->dropTable('forum_access');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190805_144852_create_tables_for_foum cannot be reverted.\n";

        return false;
    }
    */
}
