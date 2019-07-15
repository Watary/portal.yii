<?php

namespace app\modules\blog\controllers;

use app\modules\blog\models\BlogArticles;
use yii\web\Controller;

/**
 * Default controller for the `blog` module
 */
class DefaultController extends Controller
{
    public $count_show_for_page = 5;

    /**
     * Renders the index view for the module
     * @param integer $page
     * @return string
     */
    public function actionIndex($page = 1)
    {
        $count_articles = BlogArticles::getCount();
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

        $articles = BlogArticles::findArticlesPage(($page-1)*$this->count_show_for_page, $this->count_show_for_page);

        return $this->render('index', [
            'articles' => $articles,
            'count_pages' => $count_pages,
            'page' => $page,
        ]);
    }
}
