<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "blog_categories".
 *
 * @property int $id
 * @property int $id_owner
 * @property int $id_parent
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property int $created_at
 * @property int $updated_at
 */
class BlogCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blog_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_owner', 'title', 'created_at'], 'required'],
            [['id_owner', 'id_parent', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'id_parent' => 'Id Parent',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getArticles(){
        return $this->hasMany(BlogArticles::className(), ['id_category' => 'id']);
    }

    public static function findListCategories(){
        $categories = BlogCategories::find()->all();

        $items_categories = [];

        foreach ($categories as $item){
            $items_categories[$item->id] = $item->title;
        }

        return $items_categories;
    }

    public static function getCount()
    {
        return BlogCategories::find()->count();
    }
}
