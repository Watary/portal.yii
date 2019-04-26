<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $id_from
 * @property int $id_to
 * @property int $date
 * @property string $text
 */
class Message extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_from', 'id_to', 'text'], 'required'],
            [['id_from', 'id_to', 'date'], 'integer'],
            [['text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_from' => 'Id From',
            'id_to' => 'Id To',
            'date' => 'Date',
            'text' => 'Text',
        ];
    }

    public function selectUser($from, $to){
        $this->user_from = (integer)$from;
        $this->user_to = (integer)$to;
    }

    /**
     * @param int $from
     * @param int $to
     * @return ActiveQuery
     */
    public static function findMessages($from, $to)
    {
        return self::find()
            ->where(['id_from' => $from, 'id_to' => $to])
            ->orWhere(['id_from' => $to, 'id_to' => $from]);
    }

}
