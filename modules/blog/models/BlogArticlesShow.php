<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "blog_articles_show".
 *
 * @property int $id
 * @property int $id_article
 * @property int $id_user
 * @property string $ip
 */
class BlogArticlesShow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_articles_show';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_article'], 'required'],
            [['id_article', 'id_user'], 'integer'],
            [['ip'], 'string', 'max' => 255],
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
            'ip' => 'Ip',
        ];
    }
}
