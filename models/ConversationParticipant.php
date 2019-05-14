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

    /**
     * Перевіряє чи бере користувач ($id_participant) учась в бесіді ($id_conversation) в даний час
     *
     * @param $id_conversation
     * @param $id_participant
     * @return int|string
     */
    public function isParticipantNow($id_conversation, $id_participant){
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'id_user' => $id_participant,
                'date_exit' => NULL,
            ])
            ->count();
    }

    /**
     * Повертає масив з всима входами і виходими користувача ($id_participant) в бесіді ($id_conversation)
     *
     * @param $id_conversation
     * @param $id_participant
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findParticipant($id_conversation, $id_participant){
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'id_user' => $id_participant,
            ])
            ->all();
    }

    /**
     * Повертає кількість входів і виходів користувача ($id_participant) в бесіду ($id_conversation)
     *
     * @param $id_conversation
     * @param $id_participant
     * @return int|string
     */
    public function isParticipant($id_conversation, $id_participant){
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'id_user' => $id_participant,
            ])
            ->count();
    }

    /**
     * Повертає всі бесіди в яких бере участь користувач ($id_participant), кожен вхід в бесіду є окремим елементом в масиві
     *
     * @param null $id_participant
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllConversationsForUser($id_participant = NULL){
        if(!$id_participant) $id_participant = Yii::$app->user->getId();
        return ConversationParticipant::find()
            ->where(['id_user' => $id_participant])
            ->all();
    }
}
