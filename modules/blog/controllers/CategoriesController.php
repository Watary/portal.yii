<?php

namespace app\modules\blog\controllers;

use Yii;
use app\modules\blog\models\BlogCategories;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\blog\models\BlogArticles;

/**
 * CategoriesController implements the CRUD actions for BlogCategories model.
 */
class CategoriesController extends Controller
{
    public $count_show_for_page = 5;

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
     * Lists all BlogCategories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BlogCategories::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogCategories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($alias, $page = 1)
    {
        $model = BlogCategories::find()->where(['alias' => $alias])->one();

        if(!$model) {
            $model = new BlogCategories();
            $model->id = NULL;
            $model->alias = 'uncategorized';
            $model->title = 'Uncategorized';
            $model->description = 'Uncategorized';
            $model->id_parent = NULL;
            //$model->id_owner = ;
            //$model->created_at = ;
            $model->updated_at = NULL;
        }

        $count_articles = BlogArticles::getCountInCategory($model->id);
        $count_pages = ceil($count_articles/$this->count_show_for_page);

        if($page < 1){
            $page = 1;
        }elseif($page > $count_pages){
            $page = $count_pages;
        }

        if($count_articles < $this->count_show_for_page){
            $this->count_show_for_page = $count_articles;
            $page = 1;
        }

        $articles = BlogArticles::findArticlesCategoryPage(($page-1)*$this->count_show_for_page, $this->count_show_for_page, $model->id);

        return $this->render('view', [
            'model' => $model,
            'articles' => $articles,
            'count_pages' => $count_pages,
            'page' => $page,
        ]);
    }

    /**
     * Creates a new BlogCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BlogCategories();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BlogCategories model.
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
     * Deletes an existing BlogCategories model.
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
     * Finds the BlogCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogCategories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
