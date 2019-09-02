<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\galleries\models\GalleryGalleries */

$this->title = 'Create Gallery Galleries';
$this->params['breadcrumbs'][] = ['label' => 'Gallery Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-galleries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
