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
    public function actionUpdate($alias)
    {
        //$model = $this->findModel($id);
        $model = GalleryGalleries::findGallery($alias);

        if ($model->load(Yii::$app->request->post())) {

            $model->setting = $this->generateGallerySettings(Yii::$app->request->post());
            $model->id_owner = Yii::$app->user->getId();

            if($model->save()){
                //return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['galleries/update/'.$model->alias]);
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

        // thumbnail (general settings)
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
            if($request['thumbnailDisplayTransition'] && $request['thumbnailDisplayTransition'] != 'fadeIn'){
                $setting['thumbnailDisplayTransition'] = $request['thumbnailDisplayTransition'];
            }
            if($request['thumbnailBorderHorizontal'] && $request['thumbnailBorderHorizontal'] != 2){
                $setting['thumbnailBorderHorizontal'] = $request['thumbnailBorderHorizontal'];
            }
            if($request['thumbnailBorderVertical'] && $request['thumbnailBorderVertical'] != 2){
                $setting['thumbnailBorderVertical'] = $request['thumbnailBorderVertical'];
            }
            if($request['thumbnailDisplayTransitionDuration'] && $request['thumbnailDisplayTransitionDuration'] != 240){
                $setting['thumbnailDisplayTransitionDuration'] = $request['thumbnailDisplayTransitionDuration'];
            }
            if($request['thumbnailDisplayInterval'] && $request['thumbnailDisplayInterval'] != 15){
                $setting['thumbnailDisplayInterval'] = $request['thumbnailDisplayInterval'];
            }

        // thumbnailLabel (title and description)
            if($request['thumbnailLabel']['position'] && $request['thumbnailLabel']['position'] != 'overImageOnBottom'){
                $setting['thumbnailLabel']['position'] = $request['thumbnailLabel']['position'];
            }
            if($request['thumbnailLabel']['align'] && $request['thumbnailLabel']['align'] != 'center'){
                $setting['thumbnailLabel']['align'] = $request['thumbnailLabel']['align'];
            }
            if(!$request['thumbnailLabel']['display']){
                $setting['thumbnailLabel']['display'] = 'false';
            }
            if($request['thumbnailLabel']['displayDescription']){
                $setting['thumbnailLabel']['displayDescription'] = 'true';
            }
            if(!$request['thumbnailLabel']['hideIcons']){
                $setting['thumbnailLabel']['hideIcons'] = 'false';
            }
            if($request['thumbnailLabel']['titleMultiLine'] && $request['thumbnailLabel']['position'] != 'onBottom'){
                $setting['thumbnailLabel']['titleMultiLine'] = 'true';
            }
            if($request['thumbnailLabel']['titleFontSize'] && $request['thumbnailLabel']['titleFontSize'] != '1em'){
                $setting['thumbnailLabel']['titleFontSize'] = $request['thumbnailLabel']['titleFontSize'];
            }
            if($request['thumbnailLabel']['descriptionMultiLine'] && $request['thumbnailLabel']['position'] != 'onBottom'){
                $setting['thumbnailLabel']['descriptionMultiLine'] = 'true';
            }
            if($request['thumbnailLabel']['descriptionFontSize'] && $request['thumbnailLabel']['descriptionFontSize'] != '0.8em'){
                $setting['thumbnailLabel']['descriptionFontSize'] = $request['thumbnailLabel']['descriptionFontSize'];
            }

        // galleryTheme -> thumbnail (color)
            if($request['galleryTheme']['thumbnail']['borderColor'] && $request['galleryTheme']['thumbnail']['borderColor'] != 'rgba(0,0,0,1)'){
                $setting['galleryTheme']['thumbnail']['borderColor'] = $request['galleryTheme']['thumbnail']['borderColor'];
            }
            if($request['galleryTheme']['thumbnail']['background'] && $request['galleryTheme']['thumbnail']['background'] != 'rgba(68,68,68,1)') {
                $setting['galleryTheme']['thumbnail']['background'] = $request['galleryTheme']['thumbnail']['background'];
            }
            if($request['galleryTheme']['thumbnail']['titleColor'] && $request['galleryTheme']['thumbnail']['titleColor'] != 'rgba(238,238,238,1)') {
                $setting['galleryTheme']['thumbnail']['titleColor'] = $request['galleryTheme']['thumbnail']['titleColor'];
            }
            if($request['galleryTheme']['thumbnail']['titleBgColor'] && $request['galleryTheme']['thumbnail']['titleBgColor'] != 'rgba(0,0,0,0)' && $request['galleryTheme']['thumbnail']['titleBgColor'] != 'transparent') {
                $setting['galleryTheme']['thumbnail']['titleBgColor'] = $request['galleryTheme']['thumbnail']['titleBgColor'];
            }
            if($request['galleryTheme']['thumbnail']['descriptionColor'] && $request['galleryTheme']['thumbnail']['descriptionColor'] != 'rgba(204,204,204,1)') {
                $setting['galleryTheme']['thumbnail']['descriptionColor'] = $request['galleryTheme']['thumbnail']['descriptionColor'];
            }
            if($request['galleryTheme']['thumbnail']['descriptionBgColor'] && $request['galleryTheme']['thumbnail']['descriptionBgColor'] != 'rgba(0,0,0,0)' && $request['galleryTheme']['thumbnail']['descriptionBgColor'] != 'transparent') {
                $setting['galleryTheme']['thumbnail']['descriptionBgColor'] = $request['galleryTheme']['thumbnail']['descriptionBgColor'];
            }
            if($request['galleryTheme']['thumbnail']['labelBackground'] && $request['galleryTheme']['thumbnail']['labelBackground'] != 'rgba(34,34,34,0)') {
                $setting['galleryTheme']['thumbnail']['labelBackground'] = $request['galleryTheme']['thumbnail']['labelBackground'];
            }
            if($request['galleryTheme']['thumbnail']['stackBackground'] && $request['galleryTheme']['thumbnail']['stackBackground'] != 'rgba(170,170,170,1)') {
                $setting['galleryTheme']['thumbnail']['stackBackground'] = $request['galleryTheme']['thumbnail']['stackBackground'];
            }
            if($request['galleryTheme']['thumbnail']['labelOpacity'] != '100') {
                if($request['galleryTheme']['thumbnail']['labelOpacity']){
                    $setting['galleryTheme']['thumbnail']['labelOpacity'] = $request['galleryTheme']['thumbnail']['labelOpacity']/100;
                }else{
                    $setting['galleryTheme']['thumbnail']['labelOpacity'] = '0';
                }
            }
            if($request['galleryTheme']['thumbnail']['titleShadow']['enable']){
                $setting['galleryTheme']['thumbnail']['titleShadow'] = $request['galleryTheme']['thumbnail']['titleShadow']['x']."px ".$request['galleryTheme']['thumbnail']['titleShadow']['y']."px ".$request['galleryTheme']['thumbnail']['titleShadow']['r']."px ".$request['galleryTheme']['thumbnail']['titleShadow']['color'];
            }
            if($request['galleryTheme']['thumbnail']['descriptionShadow']['enable']){
                $setting['galleryTheme']['thumbnail']['descriptionShadow'] = $request['galleryTheme']['thumbnail']['descriptionShadow']['x']."px ".$request['galleryTheme']['thumbnail']['descriptionShadow']['y']."px ".$request['galleryTheme']['thumbnail']['descriptionShadow']['r']."px ".$request['galleryTheme']['thumbnail']['descriptionShadow']['color'];
            }

        // Navigation / Filters
            if($request['galleryFilterTags'] && $request['galleryFilterTags'] != 'false'){
                $setting['galleryFilterTags'] = $request['galleryFilterTags'];
            }
            if($request['navigationFontSize'] && $request['navigationFontSize'] != '1.2em'){
                $setting['navigationFontSize'] = $request['navigationFontSize'];
            }
            if(!$request['displayBreadcrumb']){
                $setting['displayBreadcrumb'] = 'false';
            }
            if(!$request['breadcrumbOnlyCurrentLevel']){
                $setting['breadcrumbOnlyCurrentLevel'] = 'false';
            }
            if(!$request['breadcrumbAutoHideTopLevel']){
                $setting['breadcrumbAutoHideTopLevel'] = 'false';
            }
            if(!$request['breadcrumbHideIcons']){
                $setting['breadcrumbHideIcons'] = 'false';
            }
            if($request['thumbnailLevelUp']){
                $setting['thumbnailLevelUp'] = 'true';
            }
            if(!$request['locationHash']){
                $setting['locationHash'] = 'false';
            }
            if(!$request['touchAnimation']){
                $setting['touchAnimation'] = 'false';
            }

        // Gallery
            if(!$request['thumbnailOpenImage']){
                $setting['thumbnailOpenImage'] = 'false';
            }
            if($request['galleryDisplayMode'] && $request['galleryDisplayMode'] != 'fullContent'){
                $setting['galleryDisplayMode'] = $request['galleryDisplayMode'];
            }
            if($request['galleryDisplayMoreStep'] && $request['galleryDisplayMoreStep'] != '2' && $request['galleryDisplayMode'] == 'moreButton'){
                $setting['galleryDisplayMoreStep'] = $request['galleryDisplayMoreStep'];
            }
            if($request['galleryPaginationMode'] && $request['galleryPaginationMode'] != 'rectangles' && $request['galleryDisplayMode'] == 'pagination'){
                $setting['galleryPaginationMode'] = $request['galleryPaginationMode'];
            }
            if($request['galleryMaxRows'] && $request['galleryMaxRows'] != '2' && ($request['galleryDisplayMode'] == 'rows' || $request['galleryDisplayMode'] == 'pagination')){
                $setting['galleryMaxRows'] = $request['galleryMaxRows'];
            }
            if($request['paginationVisiblePages'] && $request['paginationVisiblePages'] != '10' && ($request['galleryDisplayMode'] == 'pagination' || $request['galleryDisplayMode'] == 'numbers')){
                $setting['paginationVisiblePages'] = $request['paginationVisiblePages'];
            }
            if($request['galleryMaxItems'] && $request['galleryMaxItems'] != '0'){
                $setting['galleryMaxItems'] = $request['galleryMaxItems'];
            }
            if($request['gallerySorting'] && $request['gallerySorting'] != 'none'){
                $setting['gallerySorting'] = $request['gallerySorting']; // none, titleAsc, titleDesc, reversed, random
            }
            if($request['galleryDisplayTransition'] && $request['galleryDisplayTransition'] != 'none'){
                $setting['galleryDisplayTransition'] = $request['galleryDisplayTransition']; // none, rotateX, slideUp
            }
            if($request['galleryDisplayTransitionDuration'] && $request['galleryDisplayTransitionDuration'] != '1000'){
                $setting['galleryDisplayTransitionDuration'] = $request['galleryDisplayTransitionDuration']; // Duration of the gallery display transition, in milliseconds.
            }
            if($request['galleryRenderDelay'] && $request['galleryRenderDelay'] != '60'){
                $setting['galleryRenderDelay'] = $request['galleryRenderDelay']; // Delay in ms before starting the gallery rendering.
            }
            if(!$request['paginationSwipe']){
                $setting['paginationSwipe'] = 'false'; // true
            }
            if($request['galleryLastRowFull']){
                $setting['galleryLastRowFull'] = 'true'; // false
            }
            if(!$request['galleryResizeAnimation']){
                $setting['galleryResizeAnimation'] = 'false'; // true
            }
            if($request['thumbnailSelectable']){
                $setting['thumbnailSelectable'] = 'true'; // false
            }
            if(!$request['thumbnailDisplayOutsideScreen']){
                $setting['thumbnailDisplayOutsideScreen'] = 'false'; // true //Delay in ms before starting the gallery rendering.
            }

        // Hover effects
        foreach ($request['hover_effects'] as $item){
            if($item['element'] != 'remove'){
                $effect = $item['element'];
                if($item['effect'] != 'none'){
                    $effect .= '_' . $item['effect'];
                }else{
                    $effect .= '_' . $item['custom_effect'];
                }
                if($item['from']) $effect .= '_'.$item['from'];
                if($item['to']) $effect .= '_'.$item['to'];
                if($item['duration']) $effect .= '_'.$item['duration'];

                if($setting['thumbnailHoverEffect2'] == '') {
                    $setting['thumbnailHoverEffect2'] .= $effect;
                }else{
                    $setting['thumbnailHoverEffect2'] .= '|'.$effect;
                }
            }
        }

        //$setting['thumbnailHoverEffect2'] = 'image_rotateY_0deg_360deg_1000|image_rotateX_0deg_360deg_1000|image_grayscale_0%_100%_1000|label_opacity_0_1_1000';

        return json_encode($setting);
    }
}
