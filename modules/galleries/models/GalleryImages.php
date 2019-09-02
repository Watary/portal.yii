<?php

namespace app\modules\galleries\models;

use Yii;

/**
 * This is the model class for table "gallery_images".
 *
 * @property int $id
 * @property int $gallery
 * @property string $title
 * @property string $description
 * @property string $path
 * @property string $path_thumbnail
 * @property int $id_deleted
 * @property int $deleted_at
 * @property int $created_at
 * @property int $updated_at
 */
class GalleryImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gallery', 'path'], 'required'],
            [['gallery', 'id_deleted', 'deleted_at', 'created_at', 'updated_at'], 'integer'],
            [['description', 'path', 'path_thumbnail'], 'string'],
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
            'gallery' => 'Gallery',
            'title' => 'Title',
            'description' => 'Description',
            'path' => 'Path',
            'path_thumbnail' => 'Path Thumbnail',
            'id_deleted' => 'Id Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getCount(){
        return GalleryImages::find()
            ->count();
    }
}
