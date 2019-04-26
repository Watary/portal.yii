<?php
/**
 * @var yiiwebView $this
 * @var appmodelsMessage $message
 * @var yiidbActiveQuery $messagesQuery
 */
?>
<?php yii\widgets\Pjax::begin([
    'id' => 'list-messages',
    'enablePushState' => false,
    'formSelector' => false,
    'linkSelector' => false
]) ?>
<?= $this->render('_list', compact('messagesQuery', 'user', 'userFrom', 'userTo')) ?>
<?php yii\widgets\Pjax::end() ?>
<?php yii\widgets\ActiveForm::begin(['options' => ['class' => 'pjax-form']]) ?>
<?= yii\bootstrap\Html::activeTextarea($message, 'text', ['class' => 'form-control']) ?>
<?= yii\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
<?php yii\widgets\ActiveForm::end() ?>