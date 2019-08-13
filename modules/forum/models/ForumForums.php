<?php

namespace app\modules\forum\models;

use Yii;

/**
 * This is the model class for table "forum_forums".
 *
 * @property int $id
 * @property int $id_parent
 * @property string $title
 * @property string $description
 * @property string $alias
 * @property int $id_owner
 * @property int $close
 * @property int $hot
 * @property int $count_forums
 * @property int $count_topics
 * @property int $count_posts
 * @property int $id_deleted
 * @property int $deleted_at
 * @property int $created_at
 * @property int $updated_at
 */
class ForumForums extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_forums';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parent', 'id_owner', 'close', 'hot', 'count_forums', 'count_topics', 'count_posts', 'id_deleted', 'deleted_at', 'created_at', 'updated_at'], 'integer'],
            [['title', 'id_owner'], 'required'],
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
            'id_parent' => 'Parent',
            'title' => 'Title',
            'description' => 'Description',
            'alias' => 'Alias',
            'id_owner' => 'Id Owner',
            'close' => 'Close',
            'hot' => 'Hot',
            'count_forums' => 'Count Forums',
            'count_topics' => 'Count Topics',
            'count_posts' => 'Count Posts',
            'id_deleted' => 'Id Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function findListForums(){
        $forums = ForumForums::find()->all();

        $items_forums = [];

        foreach ($forums as $item){
            $items_forums[$item->id]['value'] = $item->title;
            $items_forums[$item->id]['parent'] = $item->id_parent;
        }

        return ForumForums::constructListInForums($items_forums, NULL);
    }

    public static function incrementCountForumsOfParent($parent){
        $model = ForumForums::findOne($parent);
        $model->count_forums++;
        if($model->save()){
            if($model->id_parent) {
                return ForumForums::incrementCountForumsOfParent($model->id_parent);
            }
        }
    }

    private static function constructListInForums($items_forums, $parent){
        $return = [];

        if($parent) {
            $separator = ' - ';
        }

        foreach ($items_forums as $key => $item){
            if($item['parent'] == $parent){
                $return[$key] = $separator.$item['value'];
                $array = ForumForums::constructListInForums($items_forums, $key);
                foreach ($array as $key_in => $value){
                    $return[$key_in] = $separator.$value;
                }
            }
        }

        return $return;
    }
}
