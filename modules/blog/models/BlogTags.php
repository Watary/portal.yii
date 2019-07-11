<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "blog_tags".
 *
 * @property int $id
 * @property int $id_owner
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class BlogTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_owner', 'title', 'alias'], 'required'],
            [['id_owner', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'alias'], 'string', 'max' => 255],
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_owner' => 'Id Owner',
            'title' => 'Title',
            'alias' => 'Alias',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated Ad',
        ];
    }

    public static function findListTags(){
        $tags = BlogTags::find()->all();

        $items_tags = [];

        foreach ($tags as $item){
            $items_tags[$item->id] = $item->title;
        }

        return $items_tags;
    }

    public static function getCount()
    {
        return BlogTags::find()->count();
    }

    public static function getTags()
    {
        return BlogTags::find()->all();
    }

    public static function saveTags($tags, $id_article){
        $old_tags = [];
        $new_tags = [];
        $tags_article = BlogArticleTag::findAllTagsFotArticle($id_article);
        $tags_article_array = [];
        $rus=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','і','ї',',');
        $lat=array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ','i','i','');

        if($tags_article) {
            foreach ($tags_article as $item) {
                $tags_article_array[] = $item->id_tag;
            }
        }

        if($tags) {
            foreach ($tags as $item) {
                $var = (int)$item;
                if ($item === (string)$var) {
                    $old_tags[] = $item;
                } else {
                    $new_tags[] = $item;
                }
            }
        }

        if($old_tags) {
            foreach ($old_tags as $item) {
                if (!in_array($item, $tags_article_array)) {
                    $model_article_tag = new BlogArticleTag();
                    $model_article_tag->id_article = $id_article;
                    $model_article_tag->id_tag = $item;
                    $model_article_tag->save();
                }
            }
        }

        if($tags_article_array) {
            foreach ($tags_article_array as $item) {
                if (!in_array($item, $old_tags)) {
                    $model_article_tag = BlogArticleTag::find()->where(['id_article' => $id_article])->andWhere(['id_tag' => $item])->one();
                    $model_article_tag->delete();
                }
            }
        }

        if($new_tags) {
            foreach ($new_tags as $item) {
                $model_tag = new BlogTags();

                $model_tag->id_owner = Yii::$app->user->getId();
                $model_tag->title = $item;
                $model_tag->alias = str_replace(' ', '-', trim(strtolower(str_replace($rus, $lat, preg_replace("/  +/", " ", $item)))));

                if ($model_tag->save()) {
                    $model_article_tag = new BlogArticleTag();
                    $model_article_tag->id_article = $id_article;
                    $model_article_tag->id_tag = $model_tag->id;
                    $model_article_tag->save();
                }
            }
        }
    }
}
