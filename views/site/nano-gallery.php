<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->registerCssFile('/css/nanogallery2/nanogallery2.min.css');
$this->registerJsFile('/js/nanogallery2/jquery.nanogallery2.min.js', ['depends' => [yii\web\JqueryAsset::className()]]);

$this->title = 'Gallery 1';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

    <div data-nanogallery2='{
        "itemsBaseURL": "/files/galleries/gallery_1/",
        "thumbnailWidth": "auto",
        "galleryDisplayMode": "moreButton",
        "thumbnailAlignment": "center"
    }'>
        <a href="image_1.jpg" data-ngthumb="image_1.jpg" data-ngdesc="">image 1</a>
        <a href="image_2.jpg" data-ngthumb="image_2.jpg" data-ngdesc="">image 2</a>
        <a href="image_3.jpg" data-ngthumb="image_3.jpg" data-ngdesc="">image 3</a>
        <a href="image_4.jpg" data-ngthumb="image_4.jpg" data-ngdesc="">image 4</a>
        <a href="image_5.jpg" data-ngthumb="image_5.jpg" data-ngdesc="">image 5</a>
        <a href="image_7.jpg" data-ngthumb="image_7.jpg" data-ngdesc="">image 7</a>
        <a href="image_8.jpg" data-ngthumb="image_8.jpg" data-ngdesc="">image 8</a>
        <a href="image_9.jpg" data-ngthumb="image_9.jpg" data-ngdesc="">image 9</a>
        <a href="image_10.jpg" data-ngthumb="image_10.jpg" data-ngdesc="">image 10</a>
        <a href="image_11.jpg" data-ngthumb="image_11.jpg" data-ngdesc="">image 11</a>
        <a href="image_12.jpg" data-ngthumb="image_12.jpg" data-ngdesc="">image 12</a>
        <a href="image_13.jpg" data-ngthumb="image_13.jpg" data-ngdesc="">image 13</a>
        <a href="image_14.jpg" data-ngthumb="image_14.jpg" data-ngdesc="">image 14</a>
        <a href="image_15.jpg" data-ngthumb="image_15.jpg" data-ngdesc="">image 15</a>
        <a href="image_16.jpg" data-ngthumb="image_16.jpg" data-ngdesc="">image 16</a>

    </div>
</div>
