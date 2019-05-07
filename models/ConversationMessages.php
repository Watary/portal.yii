<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conversation_messages".
 *
 * @property int $id
 * @property int $id_conversation
 * @property int $id_owner
 * @property int $date
 * @property string $text
 * @property string $remove
 */
class ConversationMessages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_conversation', 'id_owner', 'date', 'text'], 'required'],
            [['id_conversation', 'id_owner', 'date'], 'integer'],
            [['text', 'remove'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_conversation' => 'Id Conversation',
            'id_owner' => 'Id Owner',
            'date' => 'Date',
            'text' => 'Text',
            'remove' => 'Remove',
        ];
    }

    public function selectAllMessage(){
        return ConversationMessages::find()->all();
    }

    public function selectAllConversationMessage($id_conversation){
        return ConversationMessages::find()
            ->where([
                'id_conversation' => $id_conversation,
            ])
            ->all();
    }

    public function findMessage($id_conversation, $offset, $limit = 10){
        return ConversationMessages::find()->
        where([
            'id_conversation' => $id_conversation,
        ])
        ->limit($limit)
        ->offset($offset)
        ->all();
    }

    public function findLastMessage($id_conversation){
        return ConversationMessages::find()
            ->select('id')
            ->where([
                'id_conversation' => $id_conversation,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    public function findNewMessage($id_conversation, $lastIdMessage){
        return ConversationMessages::find()->
        where([
            'id_conversation' => $id_conversation,
        ])
        ->andWhere('id > '.$lastIdMessage)
        ->all();
    }

    public function countConversationMessage($id_conversation){
        return ConversationMessages::find()
            ->where([
                'id_conversation' => $id_conversation,
            ])
            ->count();
    }
}
