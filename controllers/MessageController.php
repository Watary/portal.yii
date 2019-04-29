<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\message;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for message model.
 */
class MessageController extends Controller
{
    public $user_from;
    public $user_to;

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
     * Lists all message models.
     * @return mixed
     */
    public function actionIndex($id_to)
    {
        $model = new message();

        $model->date = time();
        $model->id_from = Yii::$app->user->getId();
        $model->id_to = $id_to;
        $count = message::countMessage($model->id_from, $model->id_to);
        $lastIdMessage = message::lastIdMessage($model->id_from, $model->id_to)->id;

        $this->user_from = User::findOne($model->id_from);
        $this->user_to = User::findOne($model->id_to);

        if($model->load(Yii::$app->request->post()) && $model->save()){
            $model = new message();
        }

        return $this->render('index', [
            'model' => $model,
            'user_from' => $this->user_from,
            'user_to' => $this->user_to,
            'countMessages' => $count,
            'lastIdMessage' => $lastIdMessage,
        ]);
    }

    /**
     * Displays a single message model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id_from = Yii::$app->user->getId();
            $resalt = '';

            $model = message::findMessage($id_from, $data['id_to'], $data['startShow'], $data['countShow']);

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
     * Displays a single message model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewNewMessage()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id_from = Yii::$app->user->getId();
            $resalt = '';

            $lastIdMessage = message::lastIdMessage($id_from, $data['id_to'])->id;
            $model = message::findNewMessage($id_from, $data['id_to'], $data['lastIdMessage']);

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
     * Creates a new message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new message();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing message model.
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
     * Deletes an existing message model.
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
     * Finds the message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = message::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function constructMessage($item){
        /*$this->user_from
        $this->user_to*/
        $user = User::findOne($item->id_from);

        $result = '<div class="row" style="margin: 5px;">
            <div class="col-sm-1">
                <img src="'.$user->getAvatar().'" class="rounded" width="100%" alt="">
            </div>
            <div class="col-sm-11">
                <div><span style="font-weight: bold">'.$user->username.'</span><span class="pull-right">'.date("d.m.Y h:i:s",(integer) $item->date).'</span></div>
                <div>'.preg_replace( "#\r?\n#", "<br />", $item->text ).'</div>
            </div>
        </div><hr style="padding: 0;margin: 0;">';

        return $result;
    }
}
