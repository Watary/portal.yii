<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\ElFinder;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumPosts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="forum-posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions('elfinder',[
            'preset' => 'standard', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'height' => '500px'
        ]),
    ])->label('') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-default btn-block btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
