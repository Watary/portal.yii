<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConversationMessages */

$this->title = 'Update Conversation Messages: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Conversation Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="conversation-messages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
