<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 2019-05-02
 * Time: 15:16
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php /*$form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'text', ['template' => "{input}"])->textarea(['rows' => 3, 'id' => 'message-text'])->label(false) ?>
    <?php ActiveForm::end(); */?>

    <div id="message-text" contenteditable="true" style="border: 1px solid #747f9c; border-radius: 5px; height: 100px; width: 100%; box-shadow: 0px 0px 15px -7px #021751 inset;padding: 5px;"></div>

    <script>
        var sendMessage = document.getElementById('message-text');
        sendMessage.onkeydown = handle;

        function handle(event) {
            if (event.which === 13 && !event.shiftKey) {
                $.ajax({
                    url: 'http://portal.yii/conversation-messages/create/<?= $id_conversation ?>',
                    type: 'post',
                    data: {
                        text: sendMessage.textContent,
                        _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
                    },
                    success: function (data) {
                        //console.log(data.message);
                    }
                });
                event.preventDefault();
                sendMessage.textContent = "";
                //focusElemen('#message-text');
            }
        }
        function focusElemen($id){
            setTimeout( function() { $( $id ).focus() }, 500 );
        }
    </script>
</div>
