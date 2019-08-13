<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumForums */
/* @var $form yii\widgets\ActiveForm */
/* @var $items_forums */
?>

<div class="forum-forums-form">

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

<?php
$script =  <<< JS
    $('#generate-url').on( 'click', function( event ){
        title = document.getElementById('blogarticles-title').value;
        $.ajax({
            url         : generate_url,
            type        : 'POST',
            data        : {
                url:  title,
                article:  article,
            },
            success: function (data) {
                console.log(data.message);
                document.getElementById('blogarticles-alias').value = data.message;
            }    
        });
    
    });
JS;
$this->registerJsVar('generate_url',  \yii\helpers\Url::toRoute('/blog/articles/generate-url', true));
$this->registerJsVar('article',  $model->id);
$this->registerJs($script);
?>