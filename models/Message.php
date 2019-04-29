<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $id_from
 * @property int $id_to
 * @property int $date
 * @property string $text
 */
class Message extends \yii\db\ActiveRecord
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
            [['id_from', 'id_to', 'date', 'text'], 'required'],
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

    public function findMessage($id_from, $id_to, $offset, $limit = 10){
        return Message::find()->
            where([
                'id_from' => $id_from,
                'id_to' => $id_to,
            ])
            ->orWhere([
                'id_from' => $id_to,
                'id_to' => $id_from,
            ])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }

    public function findNewMessage($id_from, $id_to, $lastIdMessage){
        return Message::find()->
            where([
                'id_from' => $id_from,
                'id_to' => $id_to,
            ])
            ->orWhere([
                'id_from' => $id_to,
                'id_to' => $id_from,
            ])
            ->andWhere('id > '.$lastIdMessage)
            ->all();
    }

    public function lastIdMessage($id_from, $id_to){
        return Message::find()
        ->select('id')
        ->where([
            'id_from' => $id_from,
            'id_to' => $id_to,
        ])->orWhere([
            'id_from' => $id_to,
            'id_to' => $id_from,
        ])
        ->orderBy(['id' => SORT_DESC])
        ->one();
    }

    public function selectMessage($id_from, $id_to){
        return Message::find()->
            where([
                'id_from' => $id_from,
                'id_to' => $id_to,
            ])->orWhere([
                'id_from' => $id_to,
                'id_to' => $id_from,
            ])/*->limit(10)*/;
    }



    public function countMessage($id_from, $id_to){
        return Message::find()->
        where([
            'id_from' => $id_from,
            'id_to' => $id_to,
        ])->orWhere([
            'id_from' => $id_to,
            'id_to' => $id_from,
        ])->count();
    }
}
