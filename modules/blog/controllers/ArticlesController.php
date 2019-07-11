<?php

namespace app\modules\blog\controllers;

use app\modules\blog\models\BlogArticleMark;
use app\modules\blog\models\BlogArticlesShow;
use app\modules\blog\models\BlogCategories;
use app\modules\blog\models\BlogTags;
use Yii;
use app\modules\blog\models\BlogArticles;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticlesController implements the CRUD actions for BlogArticles model.
 */
class ArticlesController extends Controller
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
     * Lists all BlogArticles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BlogArticles::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BlogArticles model.
     * @param string $alias
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($alias)
    {
        $model = BlogArticles::find()->where(['alias' => $alias])->one();

        if($model){
            $this->incrementShow($model);
        }

        return $this->render('view', [
            'model' => $model,
            'isMark' => BlogArticleMark::issetMark($model->id, Yii::$app->user->getId())
        ]);
    }

    /**
     * Creates a new BlogArticles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BlogArticles();

        if ($model->load(Yii::$app->request->post())) {

            $model->id_author = Yii::$app->user->getId();

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'items_categories' => BlogCategories::findListCategories(),
            'items_tags' => BlogTags::findListTags(),
        ]);
    }

    /**
     * Updates an existing BlogArticles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $date_post = Yii::$app->request->post();

            BlogTags::saveTags($date_post['BlogArticles']['tags'], $model->id);

            if($model->save()) {
                return $this->redirect('view/' . $model->alias);
            }
        }

        $model->tags = $this->setTags($model);

        return $this->render('update', [
            'model' => $model,
            'items_categories' => BlogCategories::findListCategories(),
            'items_tags' => BlogTags::findListTags(),
        ]);
    }

    /**
     * Deletes an existing BlogArticles model.
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
     * Finds the BlogArticles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BlogArticles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlogArticles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionGenerateUrl(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $rus=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','і','ї',',');
            $lat=array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ','i','i','');

            $url = $data['url'];

            $url= preg_replace("/  +/"," ",$url);

            $url = str_replace($rus, $lat, $url);

            $url = str_replace(' ', '-', trim(strtolower($url)));

            return [
                'message' => $url,
            ];
        }

        return false;
    }

    public function actionSetMark(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if($data['mark'] > 10 || $data['mark'] < 1) return ['message' => false];

            if(BlogArticleMark::issetMark($data['article'], Yii::$app->user->getId())) return ['message' => 'Mark isset'];

            $model_mark = new BlogArticleMark();
            $model_mark->id_article = $data['article'];
            $model_mark->id_user = Yii::$app->user->getId();
            $model_mark->mark = $data['mark'];
            $model_mark->save();

            $model_article = $this->findModel($data['article']);

            $old_marks = $model_article->articlemark;
            $mark_sum = 0;
            $mark_count = 0;

            foreach ($old_marks as $item){
                $mark_sum += $item->mark;
                $mark_count++;
            }

            $model_article->mark = $mark_sum / $mark_count;
            $model_article->save();

            return [
                'message' => $mark_sum / $mark_count,
            ];
        }

        return [
            'message' => false,
        ];
    }

    private function setTags($model){
        $tags_list = [];

        foreach ($model->articletag as $item){
            $tags_list[] = $item->id_tag;
        }

        return $tags_list;
    }

    private function incrementShow($model){
        $model->count_show_all++;

        if(Yii::$app->user->isGuest){
            $model_show = BlogArticlesShow::find()->where(['id_article' => $model->id])->andWhere(['ip' => Yii::$app->getRequest()->getUserIP()])->one();
        }else{
            $model_show = BlogArticlesShow::find()->where(['id_article' => $model->id])->andWhere(['id_user' => Yii::$app->user->getId()])->one();
        }

        if(!$model_show){
            $model_articles_show = new BlogArticlesShow();
            $model_articles_show->id_article = $model->id;
            if(Yii::$app->user->isGuest){
                $model_articles_show->ip = Yii::$app->getRequest()->getUserIP();
            }else{
                $model_articles_show->id_user = Yii::$app->user->getId();
            }
            if($model_articles_show->save()){
                $model->count_show++;
            }
        }

        $model->save();

        return true;
    }
}
