<?php

use yii\helpers\Html;
use app\modules\forum\models\ForumForums;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumForums */

$this->title = 'Update Forum Forums: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forum-forums-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items_forums' => ForumForums::findListForums(),
    ]) ?>

</div>
