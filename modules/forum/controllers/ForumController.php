<?php

namespace app\modules\forum\controllers;

use app\modules\forum\models\ForumTopics;
use Yii;
use app\modules\forum\models\ForumForums;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ForumController implements the CRUD actions for ForumForums model.
 */
class ForumController extends Controller
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
     * Lists all ForumForums models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ForumForums::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'list_forums' => ForumForums::findForums(),
        ]);
    }

    /**
     * Displays a single ForumForums model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'list_forums' => ForumForums::findForums($id),
            'list_topics' => ForumTopics::findTopics($id),
            'breadcrumb_list' => ForumController::breadcrumbList($id),
        ]);
    }

    /**
     * Creates a new ForumForums model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = NULL)
    {
        $model = new ForumForums();

        if ($model->load(Yii::$app->request->post())) {

            $model->id_owner = Yii::$app->user->getId();

            if( $model->save()) {
                ForumForums::incrementCountForumsOfParent($model->id_parent);
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
     * Updates an existing ForumForums model.
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
     * Deletes an existing ForumForums model.
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
     * Finds the ForumForums model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ForumForums the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ForumForums::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function breadcrumbList($id){
        $model = ForumForums::findOne($id);

        $index = 0;

        if($model->id_parent != NULL){
            $list = ForumController::breadcrumbList($model->id_parent);

            $index = count($list);
        }

        $list[$index]['id'] = $model->id;
        $list[$index]['title'] = $model->title;

        return $list;
    }
}
