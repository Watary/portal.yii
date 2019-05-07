<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conversation".
 *
 * @property int $id
 * @property int $id_owner
 * @property int $dialog
 */
class Conversation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_owner', 'dialog'], 'integer'],
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
            'dialog' => 'Dialog',
        ];
    }
}
