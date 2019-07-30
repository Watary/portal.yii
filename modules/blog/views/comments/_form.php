<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogComments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-comments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_owner')->textInput() ?>

    <?= $form->field($model, 'id_articles')->textInput() ?>

    <?= $form->field($model, 'id_comment')->textInput() ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
