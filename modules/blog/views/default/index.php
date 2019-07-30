<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/**
 * @var array $articles
 * @var int $page
 * @var int $count_pages
 **/
$this->title = 'Blog';
$this->params['breadcrumbs'][] = ['label' => 'Blog articles'];
?>
<div class="blog">
    <?php foreach ($articles as $article) {?>
        <?= $this->render('/article',[
            'article' => $article,
        ]) ?>
    <?php } ?>
</div>

<?php if($count_pages > 1) {
        echo $this->render('/pagination/pagination',[
            'count_pages' => $count_pages,
            'page' => $page,
            'url' => 'blog/',
        ]);
    }
?>