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
use app\models\ConversationParticipant;
use app\models\ConversationMessages;
use app\models\Conversation;

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
            $id = Yii::$app->getUser()->identity->getId();
        }

        if($id != Yii::$app->getUser()->identity->getId()){
            if($id < Yii::$app->getUser()->identity->getId()){
                $dialog = $id . '-' . Yii::$app->getUser()->identity->getId();
            }else{
                $dialog = Yii::$app->getUser()->identity->getId() . '-' . $id;
            }

            $model_dialog = Conversation::find()->where(['dialog' => $dialog])->one();


            if(!$model_dialog){
                $model_dialog = new Conversation();
                $model_dialog->dialog = $dialog;
                $model_dialog->id_owner = Yii::$app->getUser()->identity->getId();
                if($model_dialog->save()){
                    ConversationController::addParticipant($id, $model_dialog->id);
                    ConversationController::addParticipant(Yii::$app->getUser()->identity->getId(), $model_dialog->id);
                }
            }

            $user['dialog-id'] = $model_dialog->id;
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
        $user['count_new_friends'] = Friend::countNewFriends($user['id']);
        $user['count_subscribers'] = Friend::countSubscribers($user['id']);
        $user['avatar'] = $model->getAvatar();
        $user['online'] = $model->isOnline($model['id']);
        $user['not_read_message'] = ConversationMessages::notReadMessages();

        if($model_dialog->id) $user['dialog-id'] = $model_dialog->id;

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

        if (Yii::$app->request->isPost && $model->imageFile = UploadedFile::getInstance($model, 'imageFile')) {
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
            if($model->removeFriend($id)) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'message' => 'Yes',
                    ];
                }
            }
        }

        return $this->redirect('/profile/view/' . $id);
    }

    public function actionIsOnline(){
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();

            return [
                'message' => User::isOnline($data['user_id']),
            ];
        }

        return false;
    }

    public function actionFriends($id = NULL){
        if(!$id){
            $id = Yii::$app->user->getId();
        }

        $model = User::getUserBuId($id);

        return $this->render('friends', [
            'user' => $model,
            'friends' => $model->friends,
            ]);
    }

    public function actionNewFriends(){
        $model = User::getUserBuId(Yii::$app->user->getId());
        $friends = Friend::findFriendRequest($model->id);

        return $this->render('new-friends', [
            'user' => $model,
            'friends' => $friends,
        ]);
    }

    public function actionSubscribers(){
        $model = User::getUserBuId(Yii::$app->user->getId());
        $friends = Friend::findSubscribers($model->id);

        return $this->render('subscribers', [
            'user' => $model,
            'friends' => $friends,
        ]);
    }

    public function actionAjaxAddFriend(){
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();

            if(!$this->isFriend(Yii::$app->user->getId())) {
                $model = new Friend();
                $model->addFriend($data['id']);
            }

            return [
                'message' => 'Yes',
            ];
        }

        return false;
    }

    public function actionAjaxRemoveFriend(){
        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();

            $model = new Friend();
            $model->removeAjaxFriend($data['id']);

            return [
                'message' => 'Yes',
            ];
        }

        return false;
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
            $this->friend = 1;
        }
        return;
    }
}
