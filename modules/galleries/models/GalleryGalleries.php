<?php

namespace app\modules\galleries\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "gallery_galleries".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $alias
 * @property string $setting
 * @property int $id_owner
 * @property int $id_deleted
 * @property int $deleted_at
 * @property int $created_at
 * @property int $updated_at
 */
class GalleryGalleries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery_galleries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'setting'], 'string'],
            [['alias', 'id_owner'], 'required'],
            [['id_owner', 'id_deleted', 'deleted_at', 'created_at', 'updated_at'], 'integer'],
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
            'title' => 'Title',
            'description' => 'Description',
            'alias' => 'Alias',
            'setting' => 'Setting',
            'id_owner' => 'Id Owner',
            'id_deleted' => 'Id Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getImages(){
        return $this->hasMany(GalleryImages::className(), ['gallery' => 'id'])->where(['deleted_at' => NULL]);
    }

    public function getOwner(){
        return $this->hasOne(User::className(), ['id' => 'id_owner']);
    }

    public static function findGallery($alias){
        return GalleryGalleries::find()->where(['alias' => $alias])->andWhere(['deleted_at' => NULL])->one();
    }

    public static function findAllGallerias(){
        return GalleryGalleries::find()->where(['deleted_at' => NULL])->all();
    }

    public static function getCount(){
        return GalleryGalleries::find()
            ->count();
    }

    public static function issetAlias($alias, $gallery){
        return GalleryGalleries::find()->andWhere(['<>','id', $gallery])->andWhere(['alias' => $alias])->count();
    }
}
