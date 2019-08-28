<?php

namespace app\modules\forum\models;

use Yii;

/**
 * This is the model class for table "forum_topics".
 *
 * @property int $id
 * @property int $id_parent
 * @property string $title
 * @property string $description
 * @property string $alias
 * @property int $id_owner
 * @property int $last_post
 * @property int $close
 * @property int $hot
 * @property int $count_posts
 * @property int $id_deleted
 * @property int $deleted_at
 * @property int $created_at
 * @property int $updated_at
 */
class ForumTopics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_topics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parent', 'title'], 'required'],
            [['id_parent', 'id_owner', 'close', 'hot', 'count_posts', 'id_deleted', 'deleted_at', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
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
            'id_parent' => 'Parent',
            'title' => 'Title',
            'description' => 'Description',
            'alias' => 'Alias',
            'id_owner' => 'Id owner',
            'last_post' => 'Last post',
            'close' => 'Close',
            'hot' => 'Hot',
            'count_posts' => 'Count Posts',
            'id_deleted' => 'Id Deleted',
            'deleted_at' => 'Deleted At',
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

    public function getParent(){
        return $this->hasOne(ForumForums::className(), ['id' => 'id_parent']);
    }

    public function getPosts(){
        return $this->hasMany(ForumPosts::className(), ['id_parent' => 'id'])->where(['deleted_at' => NULL]);
    }

    public function getLastpost(){
        return $this->hasOne(ForumPosts::className(), ['id' => 'last_post'])->where(['deleted_at' => NULL]);
    }

    public static function findTopics($id = NULL){
        return ForumTopics::find()->where(['id_parent' => $id])->andWhere(['deleted_at' => NULL])->orderBy(['hot' => SORT_DESC])->all();
    }

    public static function incrementCountPostOfParent($parent, $last_post){
        $model = ForumTopics::findOne($parent);
        $model->count_posts++;
        $model->last_post = $last_post;
        if($model->save()){
            if($model->id_parent) {
                return ForumForums::incrementCountPostOfParent($model->id_parent, $last_post);
            }
        }
        return false;
    }

    public static function decrementCountPostOfParent($parent, $last_post){
        $model = ForumTopics::findOne($parent);
        $model->count_posts--;
        if($model->save()){
            if($model->id_parent) {
                return ForumForums::decrementCountPostOfParent($model->id_parent, $last_post);
            }
        }
        return false;
    }

    public static function getCount(){
        return ForumTopics::find()
            ->count();
    }
}
