<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_parent')->widget(Select2::classname(), [
        'data' => $items_categories,
        'language' => 'en',
        'options' => [
            'placeholder' => 'Select a category ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias', ['template' => "{label}\n{hint}\n<div class='input-group'>{input}<span class='input-group-btn'><button id='generate-url' type='button' class='btn btn-default' data-dismiss='modal'>".Yii::t('app', 'Generate alias')."</button></span></div>\n{error}"])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script =  <<< JS
    $('#generate-url').on( 'click', function( event ){
        title = document.getElementById('blogcategories-title').value;
        $.ajax({
            url         : generate_url,
            type        : 'POST',
            data        : {
                url:  title,
                category:  category,
            },
            success: function (data) {
                console.log(data.message);
                document.getElementById('blogcategories-alias').value = data.message;
            }    
        });
    
    });
JS;
$this->registerJsVar('generate_url',  Url::toRoute('/blog/category/generate-url', true));
$this->registerJsVar('category',  $model->id);
$this->registerJs($script);
?>