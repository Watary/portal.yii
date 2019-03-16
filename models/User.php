<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use mdm\admin\models\User as UserModel;
use yii\helpers\Url;

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
 * @property string $online
 *
 */

class User extends UserModel
{
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
            $id = $this->id;
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