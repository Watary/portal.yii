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
 * @property int $invited
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
            [['id_conversation', 'id_user', 'id_last_see', 'date_entry', 'date_exit', 'invited'], 'integer'],
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

    public function getMessages(){
        return $this->hasMany(ConversationMessages::className(), ['id_conversation' => 'id_conversation']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * Перевіряє чи бере користувач ($id_participant) учась в бесіді ($id_conversation) в даний час
     *
     * @param $id_conversation
     * @param $id_participant
     * @return int|string
     */
    public static function isParticipantNow($id_conversation, $id_participant){
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
    public static function findParticipant($id_conversation, $id_participant){
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
    public static function isParticipant($id_conversation, $id_participant){
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
    public static function findAllConversationsForUser($id_participant = NULL){
        if(!$id_participant) $id_participant = Yii::$app->user->getId();
        return ConversationParticipant::find()
            ->where(['id_user' => $id_participant])
            ->all();
    }

    /**
     * Повертає декілька ($count) користувачів які беруть участь в бесіді ($id_conversation)
     *
     * @param $id_conversation
     * @param int $count
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findSeveralParticipant($id_conversation, $count = 3){
        return ConversationParticipant::find()
            ->where(['id_conversation' => $id_conversation])
            ->andWhere(['date_exit' => NULL])
            ->limit($count)
            ->all();
    }

    /**
     * Повертає всіх користувачів які беруть участь в бесіді ($id_conversation)
     *
     * @param $id_conversation
     * @param int $count
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllParticipant($id_conversation){
        return ConversationParticipant::find()
            ->where(['id_conversation' => $id_conversation])
            ->all();
    }

    /**
     * @param $id_conversation
     * @param null $id_participant
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findLastPFC($id_conversation, $id_participant = NULL){
        if(!$id_participant) $id_participant = Yii::$app->user->getId();
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'id_user' => $id_participant,
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    /**
     * Повертає всіх користувачів які зараз беруть участь в бесіді ($id_conversation)
     *
     * @param $id_conversation
     * @param int $count
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllParticipantNow($id_conversation){
        return ConversationParticipant::find()
            ->where([
                'id_conversation' => $id_conversation,
                'date_exit' => NULL,
            ])
            ->all();
    }

    /**
     * @param $id_conversation
     * @param null $id_participant
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function selectAllParticipantAndFriends($id_conversation){
        $participant_list = [];
        $participant_list_id = [];
        $counter = 0;
        $user = User::getUserBuId(Yii::$app->user->getId());
        $friends = $user->friends;
        $participant = ConversationParticipant::findAllParticipantNow($id_conversation);
        $isParticipant = ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId());
        $model_conversation = Conversation::findOne($id_conversation);
        foreach ($participant as $item) {
            if($isParticipant){
                if($item->invited != Yii::$app->user->getId() && $model_conversation->id_owner != Yii::$app->user->getId()){
                    $participant_list[$counter]['participant-show'] = false;
                }else{
                    $participant_list[$counter]['participant-show'] = true;
                }
            }else{
                $participant_list[$counter]['participant-show'] = false;
            }


            $participant_list[$counter]['id'] = $item->user->id;
            $participant_list[$counter]['username'] = $item->user->username;
            $participant_list[$counter]['avatar'] = $item->user->getAvatar();
            $participant_list[$counter]['participant'] = true;
            $participant_list_id[] = $item->user->id;
            $counter++;
        }
        foreach ($friends as $item) {
            if(in_array($item->friends->id, $participant_list_id)){
                continue;
            }

            $model = ConversationParticipant::findLastPFC($id_conversation, $item->friends->id);

            if($isParticipant) {
                if ($model->exclude == $item->friends->id) {
                    $participant_list[$counter]['participant-show'] = false;
                } else {
                    if ($model_conversation->id_owner == Yii::$app->user->getId()) {
                        $participant_list[$counter]['participant-show'] = true;
                    } else {
                        if ($model->invited == Yii::$app->user->getId()) {
                            $participant_list[$counter]['participant-show'] = true;
                        } else {
                            $participant_list[$counter]['participant-show'] = false;
                        }
                    }
                }
            }else{
                $participant_list[$counter]['participant-show'] = false;
            }

            $participant_list[$counter]['id'] = $item->friends->id;
            $participant_list[$counter]['username'] = $item->friends->username;
            $participant_list[$counter]['avatar'] = $item->friends->getAvatar();
            $counter++;
        }
        return $participant_list;
    }
}
