<?php

use yii\db\Migration;

/**
 * Class m190705_070029_create_tables_for_blog
 */
class m190705_070029_create_tables_for_blog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('blog_article_mark', [
            'id'                    =>  $this->primaryKey(),
            'id_article'            =>  $this->integer()->notNull(),
            'id_user'               =>  $this->integer()->notNull(),
            'mark'                  =>  $this->integer()->notNull(),
        ]);

        $this->createTable('blog_article_tag', [
            'id'                    =>  $this->primaryKey(),
            'id_article'            =>  $this->integer()->notNull(),
            'id_tag'                =>  $this->integer()->notNull(),
        ]);

        $this->createTable('blog_articles', [
            'id'                    =>  $this->primaryKey(),
            'id_category'           =>  $this->integer()->defaultValue(NULL),
            'id_author'             =>  $this->integer()->notNull(),
            'title'                 =>  $this->string(255)->notNull(),
            'text'                  =>  $this->text()->notNull(),
            'image'                 =>  $this->text()->defaultValue(NULL),
            'count_show_all'        =>  $this->integer()->notNull()->defaultValue(0),
            'count_show'            =>  $this->integer()->notNull()->defaultValue(0),
            'mark'                  =>  $this->double()->defaultValue(NULL),
            'created_at'            =>  $this->timestamp()->notNull(),
            'updated_at'            =>  $this->timestamp(),
        ]);

        $this->createTable('blog_articles_show', [
            'id'                    =>  $this->primaryKey(),
            'id_article'            =>  $this->integer()->notNull(),
            'id_user'               =>  $this->integer()->defaultValue(NULL),
            'ip'                    =>  $this->string(255)->defaultValue(NULL),
        ]);

        $this->createTable('blog_authors', [
            'id'                    =>  $this->primaryKey(),
            'id_user'               =>  $this->integer()->notNull(),
        ]);

        $this->createTable('blog_categories', [
            'id'                    =>  $this->primaryKey(),
            'id_owner'              =>  $this->integer()->notNull(),
            'id_parent'             =>  $this->integer(),
            'title'                 =>  $this->string(255)->notNull(),
            'description'           =>  $this->text(),
            'created_at'            =>  $this->timestamp()->notNull(),
            'updated_at'            =>  $this->timestamp(),
        ]);

        $this->createTable('blog_comments', [
            'id'                    =>  $this->primaryKey(),
            'id_owner'              =>  $this->integer()->notNull(),
            'id_articles'           =>  $this->integer()->notNull(),
            'id_comment'            =>  $this->integer(),
            'text'                  =>  $this->text()->notNull(),
            'created_at'            =>  $this->timestamp()->notNull(),
            'updated_at'            =>  $this->timestamp(),
        ]);

        $this->createTable('blog_tags', [
            'id'                    =>  $this->primaryKey(),
            'id_owner'              =>  $this->integer()->notNull(),
            'title'                 =>  $this->string(255)->notNull(),
            'description'           =>  $this->text(),
            'created_at'            =>  $this->timestamp()->notNull(),
            'updated_ad'            =>  $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('blog_article_mark');
        $this->dropTable('blog_article_tag');
        $this->dropTable('blog_articles');
        $this->dropTable('blog_articles_show');
        $this->dropTable('blog_authors');
        $this->dropTable('blog_categories');
        $this->dropTable('blog_comments');
        $this->dropTable('blog_tags');

        return false;
    }
}
