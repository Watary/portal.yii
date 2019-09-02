<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCssFile('/css/nanogallery2/nanogallery2.min.css');
$this->registerJsFile('/js/nanogallery2/jquery.nanogallery2.min.js', ['depends' => [yii\web\JqueryAsset::className()]]);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['/galleries']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-galleries-index">

    <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

    <div data-nanogallery2='{
        "itemsBaseURL": "/files/galleries/gallery_<?= $model->id ?>/"
        <?php foreach (json_decode($model->setting) as $key => $item){ ?>
            ,"<?= $key ?>": "<?= $item ?>"
        <?php } ?>
    }'>
        <?php foreach ($model->images as $item){ ?>
            <a href="<?= $item->path ?>" data-ngthumb="<?= $item->path_thumbnail ? $item->path_thumbnail : $item->path ?>" data-ngdesc="<?= $item->description ?>"><?= $item->title ?></a>
        <?php } ?>
    </div>

</div>