<?php

namespace app\controllers;

use Yii;
use app\models\ConversationMessages;
use app\models\ConversationParticipant;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConversationMessagesController implements the CRUD actions for ConversationMessages model.
 */
class ConversationMessagesController extends Controller
{
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

    /**
     * Lists all ConversationMessages mod
     * @param integer $id_conversation
     * @return mixed
     */
    public function actionIndex($id_conversation)
    {
        $participant = ConversationParticipant::isParticipant($id_conversation, Yii::$app->user->getId());

        if(!$participant[0]){
            throw new \yii\web\ForbiddenHttpException('Это не Ваш диалог!!!');
        }elseif($participant[0]->date_exit){
            throw new \yii\web\ForbiddenHttpException('Это не Ваш диалог!!!');
        }

        $model = new ConversationMessages();
        $countMessages = ConversationMessages::countConversationMessage($id_conversation);
        $lastIdMessage = ConversationMessages::findLastMessage($id_conversation)->id;

        $model->date = time();
        $model->id_owner = Yii::$app->user->getId();
        $model->id_conversation = $id_conversation;

        if($model->load(Yii::$app->request->post()) && $model->save()){
            $model = new ConversationMessages();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ConversationMessages::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'id_conversation' => $id_conversation,
            'countMessages' => $countMessages,
            'lastIdMessage' => $lastIdMessage,
        ]);
    }

    /**
     * Displays a single ConversationMessages model.
     * @param integer $id_conversation
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_conversation)
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $resalt = '';

            $model = ConversationMessages::findMessage($id_conversation, $data['startShow'], $data['countShow']);

            foreach ($model as $item){
                $resalt .= $this->constructMessage($item);
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'message' => $resalt,
            ];
        }
    }

    /**
     * Displays a single ConversationMessages model.
     * @param integer $id_conversation
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewNewMessage($id_conversation)
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $resalt = '';

            $lastIdMessage = ConversationMessages::findLastMessage($id_conversation)->id;
            $model = ConversationMessages::findNewMessage($id_conversation, $data['lastIdMessage']);

            foreach ($model as $item){
                $resalt .= $this->constructMessage($item);
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'message' => $resalt,
                'lastIdMessage' => $lastIdMessage,
            ];
        }
    }

    /**
     * Creates a new ConversationMessages model.
     * @param integer $id_conversation
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_conversation)
    {
        if (Yii::$app->request->isAjax && ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId())) {

            $data = Yii::$app->request->post();

            $model = new ConversationMessages();

            $model->date = time();
            $model->id_owner = Yii::$app->user->getId();
            $model->id_conversation = $id_conversation;
            $model->text = $data['text'];

            if ($model->save()) {
                return [
                    'message' => 'true',
                ];
            }else {
                return [
                    'message' => 'false',
                ];
            }
        }

        return false;
    }

    /**
     * Updates an existing ConversationMessages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ConversationMessages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ConversationMessages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConversationMessages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConversationMessages::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function constructMessage($item){
        $user = User::findOne($item->id_owner);

        $result = '<div class="row" onclick="selectMessage(this, '.$item->id.')" style="padding: 5px;">
            <div class="col-sm-1">
                <img src="'.$user->getAvatar().'" class="rounded" width="100%" alt="">
            </div>
            <div class="col-sm-11">
                <div><span style="font-weight: bold"><a href="/profile/view/'.$user->id.'">'.$user->username.'</a></span><span class="pull-right">'.date("d.m.Y H:i:s",(integer) $item->date).'</span></div>
                <div>'.preg_replace( "#\r?\n#", "<br />", $item->text ).'</div>
            </div>
        </div><hr style="padding: 0;margin: 0;">';

        return $result;
    }
}
