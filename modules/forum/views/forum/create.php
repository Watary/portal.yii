<?php

use yii\helpers\Html;
use app\modules\forum\models\ForumForums;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumForums */
$url_parent = '';
if($model->id_parent){
    $url_parent = '/'.$model->id_parent;
}

$this->title = 'Create forum';
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum'.$url_parent]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-forums-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items_forums' => ForumForums::findListForums(),
    ]) ?>

</div>
