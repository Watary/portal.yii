<?php

namespace app\controllers;

use app\models\Conversation;
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
     * @param $id_conversation
     * @return string
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionIndex($id_conversation)
    {
        if(!ConversationParticipant::isParticipant($id_conversation, Yii::$app->user->getId())){
            throw new \yii\web\ForbiddenHttpException('Это не Ваш диалог!!!');
        }

        $where = $this->getWhereDate($id_conversation, Yii::$app->user->getId());
        $model = new ConversationMessages();
        $countMessages = ConversationMessages::countConversationMessage($id_conversation);
        $lastIdMessage = ConversationMessages::findLastMessage($id_conversation, $where);
        $participant_now = ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId());
        $conversation = Conversation::findConversation($id_conversation);

        if(!$lastIdMessage){
            $lastIdMessage = 1;
        }else{
            $lastIdMessage = $lastIdMessage->id;
        }

        $model->date = time();
        $model->id_owner = Yii::$app->user->getId();
        $model->id_conversation = $id_conversation;

        if($model->load(Yii::$app->request->post()) && $model->save()){
            $model = new ConversationMessages();
        }

        $this->updateLastSee($id_conversation, $lastIdMessage);

        return $this->render('index', [
            'model' => $model,
            'id_conversation' => $id_conversation,
            'conversation_title' => $conversation->title,
            'countMessages' => $countMessages,
            'lastIdMessage' => $lastIdMessage,
            'participant_now' => $participant_now,
        ]);
    }

    /**
     * Displays a single ConversationMessages model.
     * @param integer $id_conversation
     * @return mixed
     */
    public function actionView($id_conversation)
    {
        if (Yii::$app->request->isAjax && ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId())) {
            $data = Yii::$app->request->post();
            $resalt = '';

            $where = $this->getWhereDate($id_conversation, Yii::$app->user->getId());

            $model = ConversationMessages::findMessage($id_conversation, $data['startShow'], $data['countShow'], $where);

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
     */
    public function actionRename()
    {
        $data = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && ConversationParticipant::isParticipantNow($data['id_conversation'], Yii::$app->user->getId())) {

            $model = Conversation::findConversation($data['id_conversation']);

            if($model->id_owner == Yii::$app->user->getId()) {

                $model->title = $data['title'];

                if($model->save()) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'message' => $model->title,
                    ];
                }
            }
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
        if (Yii::$app->request->isAjax && ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId())) {
            $data = Yii::$app->request->post();
            $resalt = '';

            $where = $this->getWhereDate($id_conversation, Yii::$app->user->getId());

            $lastIdMessage = ConversationMessages::findLastMessage($id_conversation, $where)->id;
            $model = ConversationMessages::findNewMessage($id_conversation, $data['lastIdMessage']);

            if($model){
                $this->updateLastSee($id_conversation, $lastIdMessage);
            }

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

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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

    public function actionRemove(){
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $selectList = $data['selectList'];
            $listRemoved = [];
            $counter = 0;

            foreach ($selectList as $key => $item){
                $model = ConversationMessages::find()->where(['id' => $key])->andWhere(['not like', 'remove', Yii::$app->user->getId()])->one();
                $model->remove = $model->remove . ' ' . Yii::$app->user->getId();
                $model->update();
                $listRemoved[$counter] = $key;
                $counter++;
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'message' => $listRemoved,
            ];
        }

        return false;
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

        $result = '<div id="conversation-messages-'.$item->id.'" class="row" onclick="selectMessage(this, '.$item->id.')" style="padding: 5px;">
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

    public function getWhereDate($id_conversation, $id_participant){
        $list = ConversationParticipant::findParticipant($id_conversation, $id_participant);
        $listConversation = ['or'];

        foreach ($list as $key => $item){
            if($item->date_exit){
                $listConversation[] = ['and', 'date>='.$item->date_entry, 'date<='.$item->date_exit];
            }else{
                $listConversation[] = ['and', 'date>='.$item->date_entry];
            }
        }

        return $listConversation;
    }

    public function updateLastSee($id_conversation, $lastIdMessage){
        $model_participant = ConversationParticipant::findLastPFC($id_conversation, Yii::$app->user->getId());
        if($model_participant->id_last_see != $lastIdMessage){
            $model_participant->id_last_see = $lastIdMessage;
            $model_participant->save();
        }
    }
}
