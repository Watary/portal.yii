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
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => \yii\helpers\Url::to(['/'],true),
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [
            ['label' => Yii::t('menu', 'Home'), 'url' => ['/']],
            ['label' => Yii::t('menu', 'Admin'), 'url' => ['/admin'], 'visible' => Yii::$app->user->can('Administrator')],
            ['label' => Yii::t('menu', 'About'), 'url' => ['/site/about']],
            ['label' => Yii::t('menu', 'Profile'), 'url' => ['/profile']],
            Yii::$app->user->isGuest ? (
                ['label' => Yii::t('menu', 'Login'), 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    Yii::t('menu', 'Logout ({username})', ['username' => Yii::$app->user->identity->username]),
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            ),
            Yii::$app->user->isGuest ? (['label' => Yii::t('menu', 'Signup'), 'url' => ['/site/signup']]) : (''),
        ];

    //$menuItems = Helper::filter($menuItems);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

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
                    <a href="#" class="list-group-item list-group-item-action active">
                        Cras justo odio
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                    <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
                    <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Vestibulum at eros</a>
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