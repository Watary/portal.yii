<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mdm\admin\components\UserStatus;
use mdm\admin\models\User as UserModel;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property integer $online
 *
 */

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;

    public $imageFile;

    public function rules()
    {
        return [
            ['status', 'in', 'range' => [UserStatus::ACTIVE, UserStatus::INACTIVE]],
            ['username', 'required', 'message' => 'Please choose a username.'],
            ['email', 'email'],
            ['email', 'required'],
            ['password_hash', 'required'],
            [['first_name', 'last_name', 'avatar'], 'string'],
            [['created_at', 'updated_at', 'id', 'online'], 'integer'],
        ];
    }

    public function getCount(){
        return User::find()->count();
    }

    public static function getUserBuId($id){
        return User::find()
            ->where(['id' => $id])
            ->one();
    }

    public function uploadFiles($folder = 'main'){
        $url = 'uploads/' . $folder . '/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
        if($this->imageFile->saveAs($url)){
            return $url;
        }else{
            return NULL;
        }
    }

    public function uploadAvatar($id){
        $url = 'uploads/avatar/avatar_' . $id . '.' . $this->imageFile->extension;
        if($this->imageFile->saveAs($url)){
            return $url;
        }else{
            return NULL;
        }
    }

    public function getFriends(){
        return $this->hasMany(Friend::className(), ['id_user' => 'id']);
    }

    public function getAvatar(){
        if ($this->avatar) {
            return Url::home(true) . $this->avatar;
        }else{
            return Url::home(true) . 'uploads/avatar/avatar.png';
        }
    }

    public function isOnline($id = NULL){
        if($id == NULL) {
            return true;
        }

        $user = self::getUserBuId($id);

        if($user['online'] < (mktime() - 300)){
            return false;
        }else{
            return true;
        }
    }

    public function updateOnline(){
        $model = User::find()
            ->where(['id' => $this->id])
            ->one();
        $model->online = mktime();
        if($model->save()) {
            $this->online = mktime();
        }
    }
}