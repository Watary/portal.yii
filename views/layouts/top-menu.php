<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => \yii\helpers\Url::to(['/'],true),
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$menuItems = [
    //['label' => Yii::t('menu', 'Home'), 'url' => ['/']],
    ['label' => Yii::t('menu', 'Admin'), 'url' => ['/admin'], 'visible' => Yii::$app->user->can('Administrator')],
    ['label' => Yii::t('menu', 'Blog'), 'url' => ['/blog']],
    ['label' => Yii::t('menu', 'Forum'), 'url' => ['/forum']],
    ['label' => Yii::t('menu', 'About'), 'url' => ['/site/about']],
    Yii::$app->user->isGuest ? (
    ['label' => Yii::t('menu', 'Login'), 'url' => ['/site/login']]
    ) : [
        'label' => Yii::$app->user->identity->username,
        'items' => [
            ['label' => Yii::t('menu', 'Profile'), 'url' => ['/profile']],
            ['label' => Yii::t('menu', 'Logout'), 'url' => ['/site/logout']],
        ],
    ],
    Yii::$app->user->isGuest ? (['label' => Yii::t('menu', 'Signup'), 'url' => ['/site/signup']]) : (''),

];

//$menuItems = Helper::filter($menuItems);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();