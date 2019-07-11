<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogArticles */

$this->title = 'Create Blog Articles';
$this->params['breadcrumbs'][] = ['label' => 'Blog Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-articles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items_categories' => $items_categories,
    ]) ?>

</div>
