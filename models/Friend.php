<?php
namespace app\models;
use Yii;
/**
 * This is the model class for table "friends".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_friend
 * @property integer $active
 * @property integer $date
 */
class Friend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'friends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_friend'], 'required'],
            [['id_user', 'id_friend', 'date'], 'integer'],
            [['active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_friend' => 'Id Friend',
            'active' => 'Active',
            'date' => 'Date',
        ];
    }

    public function addFriend($friend_id)
    {

        if($this->isFriends($friend_id, Yii::$app->getUser()->identity->getId())) return;

        if($friend = $this->isFriends(Yii::$app->getUser()->identity->getId(), $friend_id)) {
            $friend->active = true;
            $this->active = true;
            $friend->save();
        }

        $this->id_friend = $friend_id;
        $this->id_user = Yii::$app->getUser()->identity->getId();
        $this->date = time();

        return $this->save();
    }

    public function removeFriend($friend_id)
    {
        if(!$user = $this->isFriends($friend_id, Yii::$app->getUser()->identity->getId())) return;

        if($friend = $this->isFriends(Yii::$app->getUser()->identity->getId(), $friend_id)) {
            $friend->active = false;
            $friend->save();
        }

        return $user->delete();
    }

    public static function listFriends($id = NULL)
    {
        if (!$id) $id = Yii::$app->getUser()->identity->getId();
        return Friend::find()
            ->select('id_friend')
            ->where([
                'id_user' => $id,
            ])
            ->all();
    }

    public function countFriends($id = NULL)
    {
        if (!$id) $id = Yii::$app->getUser()->identity->getId();
        return Friend::find()
            ->where([
                'id_user' => $id,
            ])
            ->count();
    }

    public function getFriends(){
        return $this->hasOne(User::className(), ['id' => 'id_friend']);
    }

    public function isFriends($id_friend, $id_user = NULL)
    {
        if (!$id_user) $id_user = Yii::$app->getUser()->identity->getId();
        return Friend::find()
            ->where([
                'id_user' => $id_user,
                'id_friend' => $id_friend,
            ])
            ->one();
    }
}