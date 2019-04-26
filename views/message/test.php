<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 2019-03-25
 * Time: 17:08
 */
?>





<div id="list-messages">
    <?php
        foreach ($messages as $item){ ?>
            <img src="http://portal.yii/uploads/avatar/avatar_<?= $item['id_from'] ?>.jpg" alt="avatar" class="card-img-top" style="width: 50px;">
            <?php
            echo $item['text'] . '<br><hr>';
        }
    ?>
</div>

<?php yii\widgets\Pjax::begin([
    'timeout' => 3000,
    'enablePushState' => false,
    'linkSelector' => false,
    'formSelector' => '.pjax-form'
]) ?>
<?php yii\widgets\ActiveForm::begin(['options' => ['class' => 'pjax-form']]) ?>
<?= yii\bootstrap\Html::activeTextarea($message, 'text', ['class' => 'form-control']) ?>
<?= yii\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
<?php yii\widgets\ActiveForm::end() ?>
<?php yii\widgets\Pjax::end() ?>

<?php $this->registerJs(<<<JS
function updateList() {
  
}
setInterval(updateList, 1000);
JS
)
?>