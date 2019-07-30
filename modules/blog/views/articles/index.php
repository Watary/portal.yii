<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blog Articles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-articles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Blog Articles', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


</div>
