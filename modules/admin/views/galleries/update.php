<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\galleries\models\GalleryGalleries */

$this->title = 'Update Gallery: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Gallery Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gallery-galleries-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
