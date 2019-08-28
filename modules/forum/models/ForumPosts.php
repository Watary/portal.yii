<?php

namespace app\modules\forum\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "forum_posts".
 *
 * @property int $id
 * @property string $text
 * @property int $id_parent
 * @property int $id_owner
 * @property int $id_last_edit
 * @property int $id_deleted
 * @property int $deleted_at
 * @property int $created_at
 * @property int $updated_at
 */
class ForumPosts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string'],
            [['id_parent', 'id_owner', 'id_last_edit', 'id_deleted', 'deleted_at', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'id_parent' => 'Id Parent',
            'id_owner' => 'Owner',
            'id_last_edit' => 'Id Last Edit',
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
        return $this->hasOne(ForumTopics::className(), ['id' => 'id_parent']);
    }

    public function getOwner(){
        return $this->hasOne(User::className(), ['id' => 'id_owner']);
    }

    public static function getCount(){
        return ForumPosts::find()
            ->count();
    }
}
