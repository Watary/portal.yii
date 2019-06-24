<?php

namespace app\controllers;

use app\models\ConversationParticipant;
use app\models\ConversationMessages;
use app\models\User;
use app\models\Friend;
use Yii;
use app\models\Conversation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * ConversationController implements the CRUD actions for Conversation model.
 */
class ConversationController extends Controller
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
     * Lists all Conversation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = ConversationParticipant::findAllConversationsForUser(Yii::$app->user->getId());
        $listConversation = [];
        $lastMessage = [];
        $array_id = [];

        foreach ($model as $key => $item){
            $listConversation[$item->id_conversation][] = [
                'id_conversation' => $item->id_conversation,
                'id_last_see' => $item->id_last_see,
                'date_entry' => $item->date_entry,
                'date_exit' => $item->date_exit
            ];
        }

        foreach ($listConversation as $key => $item){
            if(!Conversation::findConversation($item[0]['id_conversation'])){
                unset($listConversation[$item[0]['id_conversation']]);
                continue;
            }

            $array_id[] = $item[0]['id_conversation'];

            $listConversation[$item[0]['id_conversation']]['conversation'] = Conversation::findConversation($item[0]['id_conversation']);
            $lastMessage[] = ConversationMessages::findLastMessage($item[0]['id_conversation'], ConversationMessages::getWhereDate($item));
            $listConversation[$item[0]['id_conversation']]['message'] = ConversationMessages::findLastMessage($item[0]['id_conversation'], ConversationMessages::getWhereDate($item));
            $whereParticipant = [];

            if($listConversation[$item[0]['id_conversation']]['conversation']->title == NULL){
                $participant = ConversationParticipant::findSeveralParticipant($item[0]['id_conversation']);
                $whereParticipant = ['or'];

                foreach ($participant as $key_in => $item_in){
                    $whereParticipant[] = ['id' => $item_in->id_user];
                }

                $participant = User::getUserBuIdWhere($whereParticipant);
                $title = '';
                foreach ($participant as $key_in => $item_in) {
                    $title .= ' '.$item_in->username.',';
                }

                $listConversation[$item[0]['id_conversation']]['conversation']->title = rtrim($title,",");
            }

            $listConversation[$item[0]['id_conversation']]['count_not_read'] = ConversationMessages::countNotReadMessages($item[0]['id_conversation']);

            if($listConversation[$item[0]['id_conversation']]['message']->date){
                $listConversation[$item[0]['id_conversation']]['date'] = $listConversation[$item[0]['id_conversation']]['message']->date;
            }else{
                $listConversation[$item[0]['id_conversation']]['date'] = $listConversation[$item[0]['id_conversation']]['conversation']->date_create;
            }
        }

        for($i = 0; $i < count($array_id); $i++){
            for($j = $i; $j < count($array_id); $j++){
                if($listConversation[$array_id[$i]]['date'] < $listConversation[$array_id[$j]]['date']){
                    $val = $listConversation[$array_id[$j]];
                    $listConversation[$array_id[$j]] = $listConversation[$array_id[$i]];
                    $listConversation[$array_id[$i]] = $val;
                }
            }
        }

        $user = User::getUserBuId(Yii::$app->user->getId());
        $friends = $user->friends;

        return $this->render('index', [
            'listConversation' => $listConversation,
            'friends' => $friends,
        ]);
    }

    /*public function actionIndex()
    {
        $model = ConversationParticipant::findAllConversationsForUser(Yii::$app->user->getId());
        $listConversation = [];
        $lastMessage = [];
        $array_id = [];

        foreach ($model as $key => $item){
            $listConversation[$item->id_conversation][] = [
                'id_conversation' => $item->id_conversation,
                'id_last_see' => $item->id_last_see,
                'date_entry' => $item->date_entry,
                'date_exit' => $item->date_exit
            ];
        }

        $whereConversation = ['or'];

        foreach ($listConversation as $key => $item){
            $array_id[] = $item[0]['id_conversation'];
            $whereConversation[] = ['conversation.id' => $item[0]['id_conversation']];
        }

        echo '<pre>';
        print_r(Conversation::findListConversation(''));
        echo '</pre>';
        exit;

        $user = User::getUserBuId(Yii::$app->user->getId());
        $friends = $user->friends;

        return $this->render('index', [
            'listConversation' => $listConversation,
            'friends' => $friends,
        ]);
    }*/

    /**
     * Displays a single Conversation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Conversation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $result =  [
            'message' => 'No',
        ];

        if (Yii::$app->request->isAjax) {
            $model = new Conversation();
            $data = Yii::$app->request->post();

            $model->title = $data['title'];
            $model->id_owner = Yii::$app->user->getId();

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if($model->save()){
                $this->addParticipant(Yii::$app->user->getId(), $model->id);
                foreach ($data['selectList'] as $key => $item){
                    $this->addParticipant($key, $model->id, Yii::$app->user->getId());
                }

                $result =  [
                    'message' => 'Yes',
                ];
            }
        }

        return $result;
    }

    function addParticipant($participant_id, $conversation_id, $invited = NULL){
        $model_participant = new ConversationParticipant();

        $model_participant->id_conversation =$conversation_id;
        $model_participant->id_user = $participant_id;
        $model_participant->id_last_see = 0;
        $model_participant->date_entry = time();

        if($invited){
            $model_participant->invited = $invited;
        }

        $model_participant->save();
    }

    /**
     * Updates an existing Conversation model.
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
     * Deletes an existing Conversation model.
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
     * Finds the Conversation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Conversation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conversation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpload($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->image = UploadedFile::getInstance($model, $data['image']);
            if ($model->image = $model->uploadImage($id)) {
                $model->save();
                return;
            }
        }

        //return $this->render('upload', ['model' => $model]);
    }


    public function actionRemove($id_conversation){
        $data['id_conversation'] = $id_conversation;

        $model = Conversation::find()->where(['id' => $data['id_conversation']])->andWhere(['not like', 'remove', Yii::$app->user->getId()])->one();
        $model->remove = $model->remove . ' ' . Yii::$app->user->getId();
        if($model->update()){
            return $this->redirect(Url::toRoute('/conversation', true));
            /*return [
                'message' => 'Yes',
            ];*/
        }else{
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

}
