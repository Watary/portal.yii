<?php

namespace app\modules\galleries\controllers;

use app\modules\galleries\models\GalleryGalleries;
use yii\web\Controller;

/**
 * Default controller for the `galleries` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = GalleryGalleries::findAllGallerias();

        return $this->render('index',[
            "model" => $model,
        ]);
    }
}
