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
            [['alias', 'description'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'id_parent' => 'Parent',
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

    public static function issetAlias($alias, $category){
        return BlogCategories::find()->andWhere(['<>','id', $category])->andWhere(['alias' => $alias])->count();
    }

    public static function getCount()
    {
        return BlogCategories::find()->count();
    }
}
