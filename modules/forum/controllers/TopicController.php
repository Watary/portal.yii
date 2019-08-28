<?php

namespace app\modules\forum\controllers;

use Yii;
use app\modules\forum\models\ForumTopics;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\forum\models\ForumForums;

/**
 * TopicController implements the CRUD actions for ForumTopics model.
 */
class TopicController extends Controller
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
     * Lists all ForumTopics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ForumTopics::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ForumTopics model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'breadcrumb_list' => ForumController::breadcrumbList($model->id_parent),
        ]);
    }

    /**
     * Creates a new ForumTopics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = NULL)
    {
        $model = new ForumTopics();

        if ($model->load(Yii::$app->request->post())) {

            $model->id_owner = Yii::$app->user->getId();

            if($model->save()) {
                ForumForums::incrementCountTopicOfParent($model->id_parent);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->id_parent = $id;

        return $this->render('create', [
            'model' => $model,
            'items_forums' => ForumForums::findListForums(),
        ]);
    }

    /**
     * Updates an existing ForumTopics model.
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
            'items_forums' => ForumForums::findListForums(),
        ]);
    }

    /**
     * Deletes an existing ForumTopics model.
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
     * Finds the ForumTopics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ForumTopics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ForumTopics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function breadcrumbList($id){
        $model = $this->findModel($id);

        $list = ForumController::breadcrumbList($model->id_parent);

        $index = count($list);

        $list[$index]['id'] = $model->id;
        $list[$index]['title'] = $model->title;

        return $list;
    }
}
