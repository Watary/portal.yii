<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConversationMessages */

$this->title = 'Create Conversation Messages';
$this->params['breadcrumbs'][] = ['label' => 'Conversation Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-messages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
