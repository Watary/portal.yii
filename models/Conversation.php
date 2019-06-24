<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

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

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['date_create', 'date_update'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['date_update'],
                ],
            ],
        ];
    }

    public function getParticipant(){
        return $this->hasMany(ConversationParticipant::className(), ['id_conversation' => 'id']);
    }

    public static function findConversation($id){
        return Conversation::find()
            ->where(['id' => $id])
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
            ->one();
    }

   /* public static function findListConversation($whereConversation){
        return ConversationParticipant::find()
            ->select('*')
            ->where(['id_user' => Yii::$app->user->getId()])
            ->orderBy(['id_conversation' => SORT_ASC])
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
            ->innerJoinWith('participant')
            ->all();
        return Conversation::find()
            ->select('*')
            ->andWhere(['not like', 'remove', Yii::$app->user->getId()])
                ->innerJoinWith('participant')
                ->Where(['conversation_participant.id_user' => '1'])
            ->all();
    }*/

    public function uploadImage($id){
        $url = 'uploads/conversations/image_' . $id . '.' . $this->image->extension;
        if($this->image->saveAs($url)){
            return $url;
        }else{
            return NULL;
        }
    }
}
