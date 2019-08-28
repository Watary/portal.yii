<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumTopics */
$url_parent = '';
if($model->id_parent){
    $url_parent = '/'.$model->id_parent;
}

$this->title = 'Create topic';
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum'.$url_parent]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-topics-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items_forums' => \app\modules\forum\models\ForumForums::findListForums(),
    ]) ?>

</div>
