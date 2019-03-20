<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'created_at')->widget(DateTimePicker::className(),[
        'name' => 'created_at',
        'type' => DateTimePicker::TYPE_INPUT,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
        'convertFormat' => true,
        'value'=> date("d.m.Y h:i",(integer) $model->online),
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy hh:i',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '01.05.2015 00:00', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]); ?>

    <?= $form->field($model, 'updated_at')->widget(DateTimePicker::className(),[
        'name' => 'updated_at',
        'type' => DateTimePicker::TYPE_INPUT,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
        'convertFormat' => true,
        'value'=> date("d.m.Y h:i",(integer) $model->online),
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy hh:i',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '01.05.2015 00:00', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]); ?>

    <?= $form->field($model, 'password_hash')->textInput() ?>

    <?= $form->field($model, 'first_name')->textInput() ?>

    <?= $form->field($model, 'last_name')->textInput() ?>

    <?= $form->field($model, 'imageFile')->fileInput()->label('Avatar') ?>

    <?= $form->field($model, 'online')->widget(DateTimePicker::className(),[
        'name' => 'online',
        'type' => DateTimePicker::TYPE_INPUT,
        'options' => ['placeholder' => 'Ввод даты/времени...'],
        'convertFormat' => true,
        'value'=> date("d.m.Y h:i",(integer) $model->online),
        'pluginOptions' => [
            'format' => 'dd.MM.yyyy hh:i',
            'autoclose'=>true,
            'weekStart'=>1, //неделя начинается с понедельника
            'startDate' => '01.05.2015 00:00', //самая ранняя возможная дата
            'todayBtn'=>true, //снизу кнопка "сегодня"
        ]
    ]); ?>

    <?= $form->field($model, 'status')->dropdownList([
        0 => 'Not active',
        10 => 'Active'
    ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
