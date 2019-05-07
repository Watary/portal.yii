<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConversationMessages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversation-messages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_conversation')->textInput() ?>

    <?= $form->field($model, 'id_owner')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'remove')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
