<?php

namespace app\controllers;

use app\models\Conversation;
use Yii;
use app\models\ConversationMessages;
use app\models\ConversationParticipant;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use yii\web\UploadedFile;

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
        $countMessages = ConversationMessages::countConversationMessage($id_conversation, $where);
        $lastMessage = ConversationMessages::findLastMessage($id_conversation, $where);
        $participant_now = ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId());
        $conversation = Conversation::findConversation($id_conversation);
        $participant_list = ConversationParticipant::selectAllParticipantAndFriends($id_conversation);

        $lastIdMessage = $lastMessage ? $lastMessage->id : 1;

        $this->updateLastSee($id_conversation, $lastIdMessage);

        return $this->render('index', [
            'id_conversation' => $id_conversation,
            'conversation_title' => $conversation->title,
            'conversation_model' => $conversation,
            'countMessages' => $countMessages,
            'lastIdMessage' => $lastIdMessage,
            'participant_now' => $participant_now,
            'participant_list' => $participant_list,
        ]);
    }

    /**
     * Displays a single ConversationMessages model.
     * @param integer $id_conversation
     * @return mixed
     */
    public function actionView($id_conversation)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();
            $resalt = '';

            $where = $this->getWhereDate($id_conversation, Yii::$app->user->getId());
            $model = ConversationMessages::findMessage($id_conversation, $data['startShow'], $where, $data['countShow']);

            foreach ($model as $item){
                $resalt .= $this->constructMessage($item);
            }

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

            $lastIdMessage = ConversationParticipant::findLastPFC($id_conversation);

            $model = ConversationMessages::findNewMessage($id_conversation, $lastIdMessage->id_last_see);

            $lastIdMessage = ConversationMessages::findLastMessage($id_conversation, $where)->id;

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

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = Yii::$app->request->post();

            $model = new ConversationMessages();

            $model->date = time();
            $model->id_owner = Yii::$app->user->getId();
            $model->id_conversation = $id_conversation;
            //$model->text = substr(strip_tags($data['text'], '<br>'), 0,-4);
            $model->text = strip_tags($data['text'], '<br>');

            if ($model->save()) {
                return [
                    'message' => $model->text,
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

    /**
     * Генерує HTML структуру для повідалення
     *
     * @param $item
     * @return string
     */
    public function constructMessage($item){
        $user = User::findOne($item->id_owner);

        $result = '<div id="conversation-messages-'.$item->id.'" class="row" onclick="selectMessage(this, '.$item->id.')" style="padding: 5px;">
            <div class="col-sm-1">
                <img src="'.$user->getAvatar().'" class="rounded" width="100%" alt="">
            </div>
            <div class="col-sm-11">
                <div><span style="font-weight: bold"><a href="/profile/'.$user->id.'">'.$user->username.'</a></span><span class="pull-right">'.date("d.m.Y H:i:s",(integer) $item->date).'</span></div>
                <div>'.preg_replace( "#\r?\n#", "<br />", $item->text ).'</div>
            </div>
        </div><hr style="padding: 0;margin: 0;">';

        return $result;
    }

    /**
     * Генерує фільтри для випору повідомлення в бесіді $id_conversation для користувача $id_participant, з урахуванням залишення і поверення в беіду цим користувачем
     *
     * @param $id_conversation
     * @param $id_participant
     * @return array
     */
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

    /**
     * Зберігає в базу даних останнє переглянуте повідомлення в бесіді $id_conversation поточним користувачем
     *
     * @param $id_conversation
     * @param $lastIdMessage
     */
    public function updateLastSee($id_conversation, $lastIdMessage){
        $model_participant = ConversationParticipant::findLastPFC($id_conversation, Yii::$app->user->getId());
        if($model_participant->id_last_see < $lastIdMessage){
            $model_participant->id_last_see = $lastIdMessage;
            $model_participant->save();
        }
    }

    /**
     * Завантаження зображень на сервер і зберігання в базу даних адреси на зобраення
     *
     * @param $id
     * @return array
     */
    public function actionUpload($id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if( isset( $data['image_upload'] ) ){

                $uploaddir = './uploads/conversations';

                if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );

                $files      = $_FILES;
                $done_files = array();

                foreach( $files as $file ){
                    $file_name = "image_$id.".pathinfo($file['name'], PATHINFO_EXTENSION);

                    if( move_uploaded_file( $file['tmp_name'], "$uploaddir/$file_name" ) ){
                        $done_files[] = realpath( "$uploaddir/$file_name" );
                    }
                }

                $data_done = $done_files ? array('files' => $done_files ) : array('error' => 'Ошибка загрузки файлов.');

                if($data_done){
                    $model = Conversation::findConversation($id);
                    $model->image = "uploads/conversations/$file_name";
                    $model->save();
                }

                return $data_done;
            }
        }
    }
}
