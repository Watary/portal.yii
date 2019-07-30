<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use mihaildev\elfinder\InputFile;
use kartik\select2\Select2;
use app\modules\blog\models\BlogComments;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogArticles */
/* @var $form yii\widgets\ActiveForm */

$model = new BlogComments();
$model->id_articles = $id_articles;
?>

<div class="blog-comments-answer-form" id="blog-comments-answer-form" style="width: 100%">

    <?php $form = ActiveForm::begin([
        'id' => 'article-comments-answer',
        'action' => Url::toRoute('/blog/comments/save', true),
        /*'enableAjaxValidation' => true,
        'validationUrl' => 'validation-rul',*/
    ]); ?>

    <?= $form->field($model, 'text')->textarea(['id' => 'comments-answer-text'])->label('') ?>
    <!--
    < ?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'options' => [
                'id' => 'comments-answer',
        ],
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[
            'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'height' => '200px',
        ]),
    ])->label('') ?>
    -->

    <?= Html::hiddenInput("id_comment") ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-default btn-block btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script =  <<< JS
    $(document).on("beforeSubmit", "#article-comments-answer", function () {
        var id_comment = null;
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
            return false;
        }
        
        form_serialize = form.serialize();
        form_serialize += '&article=' + article;
        
        var cop = document.getElementById("blog-comments-answer-form");
        document.getElementById("form-comments-answer-hidden").appendChild(cop);
        
        // submit form
        $.ajax({
            url    : form.attr('action'),
            type   : 'post',
            data   : form_serialize,
            success: function (response) {
                console.log(response);
                findComments();
            },
            error  : function () {
                console.log('internal server error');
            }
        });
            
        return false; // Cancel form submitting.
    });
JS;
$this->registerJsVar('article', $id_articles);
$this->registerJs($script);
?>