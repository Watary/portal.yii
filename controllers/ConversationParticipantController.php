<?php

namespace app\controllers;

use app\models\Conversation;
use Yii;
use app\models\ConversationParticipant;
use app\models\ConversationMessages;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConversationParticipantController implements the CRUD actions for ConversationParticipant model.
 */
class ConversationParticipantController extends Controller
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
     * Lists all ConversationParticipant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ConversationParticipant::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConversationParticipant model.
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
     * Creates a new ConversationParticipant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConversationParticipant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConversationParticipant model.
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
     * Deletes an existing ConversationParticipant model.
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

    public function actionLeave(){

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();

            if(!ConversationParticipant::isParticipantNow($data['id_conversation'], Yii::$app->user->getId())){
                return [
                    'message' => 'not participant',
                ];
            }

            $model = ConversationParticipant::findLastPFC($data['id_conversation'], Yii::$app->user->getId());

            if(!$model->date_exit){
                $model->date_exit = time();
                $model->exclude = Yii::$app->user->getId();
                if ($model->save()) {
                    return [
                        'message' => 'Yes',
                    ];
                }
            }

            return [
                'message' => 'No',
            ];
        }
    }
    public function actionReturn(){

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();

            if(ConversationParticipant::isParticipantNow($data['id_conversation'], Yii::$app->user->getId())){
                return [
                    'message' => 'You participant',
                ];
            }

            $model_LastPFC = ConversationParticipant::findLastPFC($data['id_conversation']);

            $model = new ConversationParticipant;

            $model->id_conversation = $data['id_conversation'];
            $model->id_user = Yii::$app->user->getId();
            $model->id_last_see = ConversationMessages::getIdLastMessage();
            $model->date_entry = time();
            $model->invited = $model_LastPFC->invited;

            if ($model->save()) {
                return [
                    'message' => 'Yes',
                ];
            }

            return [
                'message' => 'No',
            ];
        }
    }

    public function actionARParticipant(){

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $data = Yii::$app->request->post();
            $id_conversation = $data['id_conversation'];
            $id_participant = $data['id_participant'];

            if(!ConversationParticipant::isParticipantNow($id_conversation, Yii::$app->user->getId())){
                return [
                    'message' => 'Yor not participant',
                ];
            }

            $model = ConversationParticipant::findLastPFC($id_conversation, $id_participant);
            $model_conversation = Conversation::findOne($id_conversation);

            if(!ConversationParticipant::isParticipantNow($id_conversation, $id_participant)){
                if($id_participant == $model->exclude){
                    return [
                        'message' => 'Yor not can this doing',
                    ];
                }

                if($model_conversation && $model_conversation->id_owner != Yii::$app->user->getId()){
                    if($model_conversation->id_owner == $model->exclude){
                        return [
                            'message' => 'Yor not can this doing',
                        ];
                    }
                }

                $model_new = new ConversationParticipant;

                $model_new->id_conversation = $id_conversation;
                $model_new->id_user = $id_participant;
                $model_new->id_last_see = ConversationMessages::getIdLastMessage();
                $model_new->date_entry = time();
                $model_new->invited = Yii::$app->user->getId();

                if ($model_new->save()) {
                    return [
                        'message' => 'Yes',
                        'action' => 'Add',
                        'id' => $id_participant,
                    ];
                }
            }

            if($model->invited != Yii::$app->user->getId() && $model_conversation->id_owner != Yii::$app->user->getId()){
                return [
                    'message' => 'Yor not can this doing',
                ];
            }

            if(!$model->date_exit){
                $model->date_exit = time();
                $model->exclude = Yii::$app->user->getId();
                if ($model->save()) {
                    return [
                        'message' => 'Yes',
                        'action' => 'Remove',
                        'id' => $id_participant,
                    ];
                }
            }

            return [
                'message' => 'No',
            ];
        }
    }

    /**
     * Finds the ConversationParticipant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConversationParticipant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConversationParticipant::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
