<?php

namespace app\controllers;

use app\models\ConversationParticipant;
use app\models\ConversationMessages;
use Yii;
use app\models\Conversation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

        foreach ($model as $key => $item){
            $listConversation[$item->id_conversation][] = [
                'id_conversation' => $item->id_conversation,
                'id_last_see' => $item->id_last_see,
                'date_entry' => $item->date_entry,
                'date_exit' => $item->date_exit
            ];
        }

        $where = ['or',
            ['and', 'date>=123', 'date<=7445653'],
            ['and', 'date>=10445653'],
        ];
        $listCountMessageConversation = ConversationMessages::countConversationMessageWhere(Yii::$app->user->getId(), $where);

        echo '<pre>';
        print_r($listCountMessageConversation);
        echo '</pre>';
        exit;

        $dataProvider = new ActiveDataProvider([
            'query' => Conversation::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'listConversation' => $listConversation,
        ]);
    }

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
        $model = new Conversation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
}
