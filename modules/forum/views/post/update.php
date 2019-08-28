<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumPosts */

$this->title = 'Update Forum Posts: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum']];
$this->params['breadcrumbs'][] = ['label' => $model->parent->title, 'url' => ['/forum/'.$model->parent->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forum-posts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
