<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConversationParticipant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversation-participant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_conversation')->textInput() ?>

    <?= $form->field($model, 'id_user')->textInput() ?>

    <?= $form->field($model, 'id_last_see')->textInput() ?>

    <?= $form->field($model, 'date_entry')->textInput() ?>

    <?= $form->field($model, 'date_exit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
