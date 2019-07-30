<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogCategories */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['/blog']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['category-alias'] = $model->alias ? $model->alias : 'uncategorized';
\yii\web\YiiAsset::register($this);
?>
<div class="blog">
    <h1><?= $this->title  ?></h1>

    <div class="category-description">
        <?= $model->description  ?>
    </div>

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
        'url' => 'blog/category/'.$model->alias,
    ]);
}
?>
