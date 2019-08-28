<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumTopics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="forum-topics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php // = $form->field($model, 'alias', ['template' => "{label}\n{hint}\n<div class='input-group'>{input}<span class='input-group-btn'><button id='generate-url' type='button' class='btn btn-default' data-dismiss='modal'>".Yii::t('app', 'Generate alias')."</button></span></div>\n{error}"])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_parent')->widget(Select2::classname(), [
        'data' => $items_forums,
        'language' => 'en',
        'options' => [
            'placeholder' => 'Select a forum ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'close')->widget(Select2::classname(), [
        'data' => [
            "0" => "Відкритий",
            "1" => "Закритий",
            "2" => "Індивідуальні права",
        ],
    ]) ?>

    <?= $form->field($model, 'hot')->widget(Select2::classname(), [
        'data' => [
            "0" => "Not hot",
            "1" => "Hot",
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-default btn-block btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
