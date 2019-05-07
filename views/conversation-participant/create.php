<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConversationParticipant */

$this->title = 'Create Conversation Participant';
$this->params['breadcrumbs'][] = ['label' => 'Conversation Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-participant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
