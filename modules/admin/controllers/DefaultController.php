<?php

namespace app\modules\admin\controllers;

use app\modules\blog\models\BlogArticles;
use app\modules\blog\models\BlogCategories;
use app\modules\blog\models\BlogComments;
use app\modules\blog\models\BlogTags;
use app\modules\forum\models\ForumForums;
use app\modules\forum\models\ForumPosts;
use app\modules\forum\models\ForumTopics;
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
            'user'                  => $user,
            'countUsers'            =>  User::getCount(),
            'countLanguages'        =>  Lang::getCount(),

            'countBlogArticles'     =>  BlogArticles::getCount(),
            'countBlogCategories'   =>  BlogCategories::getCount(),
            'countBlogTags'         =>  BlogTags::getCount(),
            'countBlogComments'     =>  BlogComments::getCount(),

            'countForumForums'      =>  ForumForums::getCount(),
            'countForumTopics'      =>  ForumTopics::getCount(),
            'countForumPosts'       =>  ForumPosts::getCount(),
        ]);
    }
}
