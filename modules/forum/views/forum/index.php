<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Forum';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-forums-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Forum Forums', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'id_parent',
            'title',
            'description:ntext',
            //'alias',
            //'id_owner',
            //'close',
            //'hot',
            //'count_forums',
            //'count_topics',
            //'count_posts',
            //'id_deleted',
            //'deleted_at',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
