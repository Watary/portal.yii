<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogTags */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['/blog']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog">
    <h1><?= $this->title  ?></h1>

    <div class="category-description">
        <?= $model->description  ?>
    </div>

    <?php foreach ($articles as $article) { ?>
        <?= $this->render('/article',[
            'article' => $article->article['0'],
        ]) ?>
    <?php } ?>
</div>

<?php if($count_pages > 1) {
    echo $this->render('/pagination/pagination',[
        'count_pages' => $count_pages,
        'page' => $page,
        'url' => 'blog/category/'.$model->alias,
    ]);
}
?>
