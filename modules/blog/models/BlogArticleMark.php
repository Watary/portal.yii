<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "blog_article_mark".
 *
 * @property int $id
 * @property int $id_article
 * @property int $id_user
 * @property int $mark
 */
class BlogArticleMark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_article_mark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_article', 'id_user', 'mark'], 'required'],
            [['id_article', 'id_user', 'mark'], 'integer'],
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
            'id_user' => 'Id User',
            'mark' => 'Mark',
        ];
    }

    public static function issetMark($article, $user){
        return BlogArticleMark::find()->where(['id_article' => $article, 'id_user' => $user])->count();
    }
}
