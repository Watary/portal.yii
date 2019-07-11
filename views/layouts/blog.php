<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use app\widgets\LanguageDropdown;
use app\widgets\LanguageSelect;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use mdm\admin\components\Helper;

if(!Yii::$app->user->isGuest) {
    Yii::$app->user->identity->updateOnline();
}

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="wrap-main" class="wrap">

    <?= $this->render('top-menu.php') ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="row">
            <div class="col-sm-8 col-md-9">
                <?= $content ?>
            </div>

            <div class="col-sm-4 col-md-3">
                <div class="list-group">
                    <span class="list-group-item list-group-item-action header">
                        Categories
                    </span>
                    <a href="#" class="list-group-item list-group-item-action">WEB development</a>
                    <a href="#" class="list-group-item list-group-item-action">WEB design</a>
                    <a href="#" class="list-group-item list-group-item-action">PHP development</a>
                    <a href="#" class="list-group-item list-group-item-action">Yii2 framework</a>
                </div>

                <div class="list-group">
                    <span class="list-group-item list-group-item-action header">
                        Last articles
                    </span>
                    <a href="#" class="list-group-item list-group-item-action">WEB development</a>
                    <a href="#" class="list-group-item list-group-item-action">WEB design</a>
                    <a href="#" class="list-group-item list-group-item-action">PHP development</a>
                    <a href="#" class="list-group-item list-group-item-action">Yii2 framework</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('footer.php') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
