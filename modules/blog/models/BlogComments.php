<?php

namespace app\modules\blog\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "blog_comments".
 *
 * @property int $id
 * @property int $id_owner
 * @property int $id_articles
 * @property int $id_comment
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 */
class BlogComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_owner', 'id_articles', 'text'], 'required'],
            [['id_owner', 'id_articles', 'id_comment'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
            'id_articles' => 'Id Articles',
            'id_comment' => 'Id Comment',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public function getOwner(){
        return $this->hasOne(User::className(), ['id' => 'id_owner']);
    }

    public static function findComments($article){
        return BlogComments::find()
            ->where(['id_articles' => $article])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    public static function getCount(){
        return BlogComments::find()
            ->count();
    }

    public static function countCommentsArticle($article){
        return BlogComments::find()
            ->where(['id_articles' => $article])
            ->count();
    }
}
