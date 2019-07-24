<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "blog_article_tag".
 *
 * @property int $id
 * @property int $id_article
 * @property int $id_tag
 *
 * @property object $tag
 */
class BlogArticleTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_article_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_article', 'id_tag'], 'required'],
            [['id_article', 'id_tag'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_article' => 'Id Article',
            'id_tag' => 'Id Tag',
        ];
    }

    public function getTag(){
        return $this->hasOne(BlogTags::className(), ['id' => 'id_tag']);
    }

    public static function findAllTagsFotArticle($id_article){
        return BlogArticleTag::find()->where(['id_article' => $id_article])->all();
    }

    public static function getCountInTag($tag)
    {
        return BlogArticleTag::find()->where(['id_tag' => $tag])->count();
    }
}
