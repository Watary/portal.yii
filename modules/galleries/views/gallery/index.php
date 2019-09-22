<?php

use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCssFile('/css/nanogallery2/nanogallery2.min.css');
$this->registerJsFile('/js/nanogallery2/jquery.nanogallery2.min.js', ['depends' => [yii\web\JqueryAsset::className()]]);

$gallery_settings = Json::decode($model->setting, true);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['/galleries']];
$this->params['breadcrumbs'][] = $this->title;

function generateSetting($gallery_settings){
    $result = '';

    foreach ($gallery_settings as $key => $item){
        $result .= ",\n";
        if(is_array($item)){
            generateSetting($item);
            $result .= "\"$key\": {\n".generateSetting($item)."\n}";
        } else {
            if($item == "true" || $item == "false"){
                $result .= "\"$key\": $item";
            } else {
                $result .= "\"$key\": \"$item\"";
            }
        }
    }

    return $result;
}

?>
<div class="gallery-galleries-index">

    <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

    <div data-nanogallery2='{
        "itemsBaseURL": "/files/galleries/gallery_<?= $model->id ?>/"
        <?= generateSetting($gallery_settings) ?>
    }'>
        <?php foreach ($model->images as $item){ ?>
            <a href="<?= $item->path ?>" data-ngthumb="<?= $item->path_thumbnail ? $item->path_thumbnail : $item->path ?>" data-ngdesc="<?= $item->description ?>"><?= $item->title ?></a>
        <?php } ?>
    </div>

</div>