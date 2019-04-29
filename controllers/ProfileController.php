<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Friend;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Html;

/**
 * ProfileController implements the CRUD actions for User model.
 */
class ProfileController extends Controller
{

    public $own = false;
    public $friend = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id = NULL)
    {

        if(!$id){
            $id = Yii::$app->getUser()->identity->id;
        }

        return $this->actionView($id);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(!$id){
            $id = Yii::$app->getUser()->identity->id;
        }

        $this->isOwn($id);
        $this->isFriend($id);

        $model = User::getUserBuId($id);

        $user['id'] = $model['id'];
        $user['username'] = $model['username'];
        $user['first_name'] = $model['first_name'];
        $user['last_name'] = $model['last_name'];
        $user['email'] = $model['email'];
        $user['status'] = $model['status'];
        $user['created_at'] = $model['created_at'];
        $user['updated_at'] = $model['updated_at'];
        $user['friends'] = $model->friends;
        $user['own'] = $this->own;
        $user['friend'] = $this->friend;
        $user['count_friends'] = Friend::countFriends($user['id']);
        $user['avatar'] = $model->getAvatar();
        $user['online'] = $model->isOnline($model['id']);

        return $this->render('view', [
            'user' => $user,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(!Yii::$app->user->can('Administrator') && $model->id != Yii::$app->user->getId()){
            $this->redirect('/profile/' . $model->id);
        }

        if (Yii::$app->request->isPost && $model->imageFile  = UploadedFile::getInstance($model, 'imageFile')) {
            $model->avatar = $model->uploadAvatar($model->getId());
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionAddFriend($id = NULL)
    {
        if($id) {
            $model = new Friend();
            $model->addFriend($id);
        }
        return $this->redirect('/profile/view/' . $id);
    }

    public function actionRemoveFriend($id = NULL)
    {
        if($id) {
            $model = new Friend();
            $model->removeFriend($id);
        }
        return $this->redirect('/profile/view/' . $id);
    }

    public function actionWriteMessage($id = NULL)
    {
        echo 'write-message';
        return $this->redirect('/profile/view/' . $id);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function isOwn($id)
    {
        if (Yii::$app->getUser()->identity->id == $id){
            $this->own = true;
        }
        return;
    }

    private function isFriend($id)
    {
        $model = new Friend();
        if ($model->isFriends($id, Yii::$app->getUser()->identity->id)){
            $this->friend = true;
        }
        return;
    }
}
