<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'PORTAL YII';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::t('app', 'Congratulations!') ?></h1>

        <p class="lead"><?= Yii::t('app', 'You have successfully created your Yii-powered application.') ?></p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Блог</h2>

                <p>Блог — це веб-сайт, головний зміст якого — записи, зображення чи мультимедіа, що регулярно додаються. Для Блогів характерні короткі записи тимчасової значущості.<br>Заходьте в найцікавіший блог. </p>

                <p><a class="btn btn-default" href="<?= Url::to('blog') ?>">Блог &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
