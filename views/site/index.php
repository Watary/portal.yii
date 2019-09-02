<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'PORTAL YII';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::t('app', 'Congratulations!') ?></h1>

        <p class="lead"><?= Yii::t('app', 'You have successfully created your Yii-powered application.') ?></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Блог</h2>

                <p>Блог — це веб-сайт, головний зміст якого — записи, зображення чи мультимедіа, що регулярно додаються. Для Блогів характерні короткі записи тимчасової значущості.<br>Заходьте в найцікавіший блог. </p>

                <p><a class="btn btn-default" href="<?= Url::to('blog') ?>">Блог &raquo;</a></p>
            </div>

            <div class="col-lg-4">
                <h2>Форум</h2>

                <p>Веб-форум або просто Форум — інтернет-ресурс, популярний різновид спілкування в інтернеті. На форумі створюються теми для спілкування, що робить його кращим за чат. Всі, кого цікавить певна інформація, можуть зручно й швидко переглянути її на форумі. На форумі є адміністратори (власники форуму) та модератори (обслуговуючий персонал, який стежить за виконанням установлених правил та порядком). Форуми можуть бути присвячені програмному забезпеченню, автомобілям, футбольній команді тощо. </p>

                <p><a class="btn btn-default" href="<?= Url::to('forum') ?>">Форум &raquo;</a></p>
            </div>

            <div class="col-lg-4">
                <h2>Посилання</h2>

                <ul>
                    <li><a class="btn btn-default" href="<?= Url::to('galleries') ?>">Галерея &raquo;</a></li>
                    <li><a class="btn btn-default" href="<?= Url::to('site/owl-carousel') ?>">Слайдер &raquo;</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>
