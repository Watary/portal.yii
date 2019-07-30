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

<div class="blog-comments-form">

    <?php $form = ActiveForm::begin([
        'id' => 'article-comments',
        'action' => Url::toRoute('/blog/comments/save', true),
        /*'enableAjaxValidation' => true,
        'validationUrl' => 'validation-rul',*/
    ]); ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[
            'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'height' => '200px'
        ]),
    ])->label('') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-default btn-block btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script =  <<< JS
    $(document).on("beforeSubmit", "#article-comments", function () {
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
            return false;
        }
        
        form_serialize = form.serialize();
        form_serialize += '&article=' + article;
        
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