<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumTopics */

$this->title = 'Update Forum Topics: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Forum Topics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->parent->title, 'url' => ['forum/'.$model->parent->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forum-topics-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items_forums' => \app\modules\forum\models\ForumForums::findListForums(),
    ]) ?>

</div>
