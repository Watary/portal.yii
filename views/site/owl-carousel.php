<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'OwlCarousel2';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

    <?php
    use kv4nt\owlcarousel\OwlCarouselWidget;

    OwlCarouselWidget::begin([
        'container' => 'div',
        /*'containerOptions' => [
            'id' => 'container-id',
            'class' => 'container-class'
        ],*/
        'pluginOptions'    => [
            'items'             => 2,
            'loop'              => true,
            'nav'               => true,
        ]
    ]);
    ?>

    <div class="item"><img src="/files/user_1/Image/25241.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/4836.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/8421.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/25241.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/743487.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/277924638.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/doroga_noch_svet_125999_1920x1080.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/everest_gory_nebo_vershiny_96976_1920x1080.jpg" alt=""></div>
    <div class="item"><img src="/files/user_1/Image/Guardians_of_the_Galaxy_Vol._2_Raccoons_Rocket_523942_1920x1080.jpg" alt=""></div>

    <?php OwlCarouselWidget::end(); ?>
</div>
