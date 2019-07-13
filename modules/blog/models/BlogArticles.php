<?php

namespace app\modules\blog\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "blog_articles".
 *
 * @property int $id
 * @property int $id_category
 * @property int $id_author
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $alias
 * @property int $count_show_all
 * @property int $count_show
 * @property int $count_comments
 * @property double $mark
 * @property string $created_at
 * @property string $updated_at
 *
 * @property object $author
 * @property object $category
 * @property object $articletag
 * @property object $articlemark
 */
class BlogArticles extends \yii\db\ActiveRecord
{

    public $tags;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_category', 'id_author', 'count_show_all', 'count_show', 'count_comments'], 'integer'],
            [['id_author', 'title', 'text', 'alias'], 'required'],
            [['text', 'image'], 'string'],
            [['mark'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_category' => 'Category',
            'id_author' => 'Author',
            'title' => 'Title',
            'text' => 'Text',
            'image' => 'Image',
            'alias' => 'Alias',
            'count_show_all' => 'Count show all',
            'count_show' => 'Count show',
            'mark' => 'Mark',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function getAuthor(){
        return $this->hasOne(User::className(), ['id' => 'id_author']);
    }

    public function getCategory(){
        return $this->hasOne(BlogCategories::className(), ['id' => 'id_category']);
    }

    public function getArticletag(){
        return $this->hasMany(BlogArticleTag::className(), ['id_article' => 'id']);
    }

    public function getArticleshow(){
        return $this->hasMany(BlogArticlesShow::className(), ['id_article' => 'id']);
    }

    public function getArticlemark(){
        return $this->hasMany(BlogArticleMark::className(), ['id_article' => 'id']);
    }

    public static function getCount()
    {
        return BlogArticles::find()->count();
    }

    public static function issetAlias($alias, $articles){
        return BlogArticles::find()->andWhere(['<>','id', $articles])->andWhere(['alias' => $alias])->count();
    }
}
