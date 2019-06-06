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

    public function getParticipant(){
        return $this->hasMany(ConversationParticipant::className(), ['id_conversation' => 'id_conversation']);
    }

    /**
     * Повертає всі повідомлення, що є в базі даних
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function selectAllMessage(){
        return ConversationMessages::find()->all();
    }

    /**
     * Повертає всі повідомлення з заданої ($id_conversation) бесіди
     *
     * @param $id_conversation
     * @return array|\yii\db\ActiveRecord[]
     */
    public function selectAllConversationMessage($id_conversation){
        return ConversationMessages::find()
            ->where(['id_conversation' => $id_conversation,])
            ->all();
    }

    /**
     * Повертає задану кількість ($limit) повідомлень, з заданої ($id_conversation) бесіди починаючи, з заданої позиції ($offset) і з додатковими умовами ($where)
     * Використовується для підгрузки повідомлень з допомогою AJAX
     *
     * @param $id_conversation
     * @param $offset
     * @param int $limit
     * @param $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findMessage($id_conversation, $offset, $where, $limit = 10){
        return ConversationMessages::find()
            ->where(['id_conversation' => $id_conversation])
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
            ->andWhere($where)
            ->limit($limit)
            ->offset($offset)
            ->all();
    }

    /**
     * Повертає ідентифікатор останнього повідомлення в заданій ($id_conversation) бесіді, і з додатковивми умовами ($where)
     *
     * @param $id_conversation
     * @param $where
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findLastMessage($id_conversation, $where){
        return ConversationMessages::find()
            ->where(['id_conversation' => $id_conversation,])
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
            ->andWhere($where)
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    /**
     * Повертає повідомлення надіслані пізніше заданого ($lastIdMessage) повідомлення, в заданій ($id_conversation) бесіді
     *
     * @param $id_conversation
     * @param $lastIdMessage
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findNewMessage($id_conversation, $lastIdMessage){
        return ConversationMessages::find()
            ->where(['id_conversation' => $id_conversation,])
            ->andWhere('id > '.$lastIdMessage)
            ->all();
    }

    /**
     * Повертає кількість повідомлень в бесіді ($id_conversation) з урахуванням видалених для користувача
     *
     * @param $id_conversation
     * @return int|string
     */
    public static function countConversationMessage($id_conversation){
        return ConversationMessages::find()
            ->where(['id_conversation' => $id_conversation,])
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
            ->count();
    }

    /**
     * Повертає кількість повідомлень в бесіді ($id_conversation) з урахуванням видалених для користувача і додатковими умовами ($where)
     *
     * @param $id_conversation
     * @param $where
     * @return int|string
     */
    public static function countConversationMessageWhere($id_conversation, $where){
        return ConversationMessages::find()
            ->where(['id_conversation' => $id_conversation,])
            ->andWhere($where)
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
            ->count();
    }



    public static function countNotReadMessages($id_conversation, $where, $id_user = NULL){
        if(!$id_user){
            $id_user = Yii::$app->user->getId();
        }

        $model_participant = ConversationParticipant::findLastPFC($id_conversation, $id_user);
        $participant = ConversationParticipant::findOne($model_participant->id);
        $count_messages = $participant->getMessages()
            ->where('id > '.$model_participant->id_last_see)
            ->andWhere($where)
            ->count();

        return $count_messages;
    }

    public static function notReadMessages(){
        $model = ConversationParticipant::findAllConversationsForUser(Yii::$app->user->getId());
        $listConversation = [];
        $count_message = 0;

        foreach ($model as $key => $item){
            $listConversation[$item->id_conversation][] = [
                'id_conversation' => $item->id_conversation,
                'id_last_see' => $item->id_last_see,
                'date_entry' => $item->date_entry,
                'date_exit' => $item->date_exit
            ];
        }

        foreach ($listConversation as $key => $item){
            $listConversation[$item[0]['id_conversation']]['conversation'] = Conversation::findConversation($item[0]['id_conversation']);
            $whereParticipant = [];

            if($listConversation[$item[0]['id_conversation']]['conversation']->title == NULL){
                $participant = ConversationParticipant::findSeveralParticipant($item[0]['id_conversation']);
                $whereParticipant = ['or'];

                foreach ($participant as $key_in => $item_in){
                    $whereParticipant[] = ['id' => $item_in->id_user];
                }
            }

            $count_message += ConversationMessages::countNotReadMessages($item[0]['id_conversation'], $whereParticipant);

        }

        return $count_message;
    }

    public static function getWhereDate($list){
        $listConversation = ['or'];

        foreach ($list as $key => $item){
            if($item['date_exit']){
                $listConversation[] = ['and', 'date>='.$item['date_entry'], 'date<='.$item['date_exit']];
            }else{
                $listConversation[] = ['and', 'date>='.$item['date_entry']];
            }
        }

        return $listConversation;
    }
}
