<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">
    <?php yii\widgets\Pjax::begin(['id' => 'message-form-pjax']) ?>
        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
            <?= $form->field($model, 'text', ['template' => "{input}"])->textarea(['rows' => 3, 'id' => 'message-text'])->label(false) ?>
        <?php ActiveForm::end(); ?>

        <script>
            var sendMessage = document.getElementById('message-text');
            sendMessage.onkeydown = handle;

            function handle(event) {
                if (event.which === 13 && !event.shiftKey) {
                    event.target.form.dispatchEvent(new Event("submit", {cancelable: true}));
                    event.preventDefault();
                    focusElemen('#message-text');
                }
            }
            function focusElemen($id){
                setTimeout( function() { $( $id ).focus() }, 500 );
            }
        </script>
    <?php yii\widgets\Pjax::end(); ?>
</div>