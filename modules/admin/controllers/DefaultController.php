<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\Lang;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $user = new User();

        $user = $user->findIdentity(1);

        return $this->render('index', [
            'user' => $user,
            'countUsers' =>  User::getCount(),
            'countLanguages' =>  Lang::getCount(),
        ]);
    }
}
