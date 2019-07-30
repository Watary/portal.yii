<?php

namespace app\modules\blog\controllers;

use Yii;
use app\modules\blog\models\BlogCategories;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\blog\models\BlogArticles;
use yii\helpers\Url;

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
            $model->description = '';//'Uncategorized';
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

        $articles = BlogArticles::findArticlesCategoryPage($model->id, ($page-1)*$this->count_show_for_page, $this->count_show_for_page);

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

        if ($model->load(Yii::$app->request->post())) {

            if(!$model->alias){
                $model->alias = $model->title;
            }

            $model->id_owner = Yii::$app->user->getId();
            $model->created_at = time();
            $model->alias = $this->generateAlias($model->alias, $model->id);


            if($model->save()) {
                return $this->redirect(Url::to(['/blog/category/' . $model->alias], true));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'items_categories' => BlogCategories::findListCategories(),
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
            return $this->redirect(Url::to(['/blog/category/' . $model->alias], true));
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

    public function actionGenerateUrl(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $url = $this->generateAlias($data['url'], $data['category']);

            return [
                'message' => $url,
            ];
        }

        return false;
    }

    private function issetAlias($alias, $category){

        for(;;) {
            if (BlogCategories::issetAlias($alias, $category)) {
                $alias .= '-new';
            }else{
                break;
            }
        }

        return $alias;
    }

    private function generateAlias($alias, $category){
        $rus=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','і','ї',',');
        $lat=array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ','i','i','');

        $alias= preg_replace("/  +/"," ",$alias);

        $alias = str_replace($rus, $lat, $alias);

        $alias = str_replace(' ', '-', trim(strtolower($alias)));

        $alias = str_replace([':',';','.',',','<','>','?','#','%'], "", $alias);

        $alias = $this->issetAlias($alias, $category);

        return $alias;
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
