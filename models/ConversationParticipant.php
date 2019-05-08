<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conversation_participant".
 *
 * @property int $id
 * @property int $id_conversation
 * @property int $id_user
 * @property int $id_last_see
 * @property int $date_entry
 * @property int $date_exit
 */
class ConversationParticipant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation_participant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_conversation', 'id_user', 'id_last_see', 'date_entry'], 'required'],
            [['id_conversation', 'id_user', 'id_last_see', 'date_entry', 'date_exit'], 'integer'],
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
            'id_user' => 'Id User',
            'id_last_see' => 'Id Last See',
            'date_entry' => 'Date Entry',
            'date_exit' => 'Date Exit',
        ];
    }

    public function isParticipantNow($id_conversation, $id_participant){
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'id_user' => $id_participant,
                'date_exit' => NULL,
            ])
            ->count();
    }

    public function isParticipant($id_conversation, $id_participant){
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'id_user' => $id_participant,
            ])
            ->all();
    }
}
