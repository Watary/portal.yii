<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\galleries\models\GalleryGalleries;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GalleriesController implements the CRUD actions for GalleryGalleries model.
 */
class GalleriesController extends Controller
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
     * Lists all GalleryGalleries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => GalleryGalleries::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GalleryGalleries model.
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
     * Creates a new GalleryGalleries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GalleryGalleries();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->id_owner = Yii::$app->user->getId();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GalleryGalleries model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->setting = $this->generateGallerySettings(Yii::$app->request->post());
            $model->id_owner = Yii::$app->user->getId();

            if($model->save()){
                //return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GalleryGalleries model.
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
     * Finds the GalleryGalleries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GalleryGalleries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GalleryGalleries::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGenerateAlias(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $url = $this->generateAlias($data['url'], $data['gallery']);

            return [
                'message' => $url,
            ];
        }

        return false;
    }

    private function issetAlias($alias, $gallery){

        for(;;) {
            if (GalleryGalleries::issetAlias($alias, $gallery)) {
                $alias .= '-new';
            }else{
                break;
            }
        }

        return $alias;
    }

    private function generateAlias($alias, $gallery){
        $rus=array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','і','ї',',');
        $lat=array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ','i','i','');

        $alias= preg_replace("/  +/"," ",$alias);

        $alias = str_replace($rus, $lat, $alias);

        $alias = str_replace(' ', '-', trim(strtolower($alias)));

        $alias = str_replace([':',';','.',',','<','>','?','#','%'], "", $alias);

        $alias = $this->issetAlias($alias, $gallery);

        return $alias;
    }

    private function generateGallerySettings($request){
        $setting = [];

        if($request['gallery_layout'] == 'grid'){
            if($request['thumbnailHeight'] && $request['thumbnailHeight'] != 200 && $request['thumbnailHeight'] != 'auto'){
                $setting['thumbnailHeight'] = $request['thumbnailHeight'];
            }
            if($request['thumbnailWidth'] && $request['thumbnailWidth'] != 300 && $request['thumbnailWidth'] != 'auto'){
                $setting['thumbnailWidth'] = $request['thumbnailWidth'];
            }
        }elseif ($request['gallery_layout'] == 'justified'){
            if($request['thumbnailHeight'] && $request['thumbnailHeight'] != 200 && $request['thumbnailHeight'] != 'auto'){
                $setting['thumbnailHeight'] = $request['thumbnailHeight'];
            }
            $setting['thumbnailWidth'] = 'auto';
        }else{
            $setting['thumbnailHeight'] = 'auto';
            if($request['thumbnailWidth'] && $request['thumbnailWidth'] != 300 && $request['thumbnailWidth'] != 'auto'){
                $setting['thumbnailWidth'] = $request['thumbnailWidth'];
            }
        }

        return json_encode($setting);
    }
}
