<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogArticles */

$this->title = 'Update articles: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['articles/view/'.$model->alias]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="blog-articles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items_categories' => $items_categories,
        'items_tags' => $items_tags,
    ]) ?>

</div>
