<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumPosts */

$this->title = 'Create post';
$this->params['breadcrumbs'][] = ['label' => 'Forum Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-posts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
