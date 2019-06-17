<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lang-index">

    <p>
        <?= Html::a(Yii::t('admin', 'Add language'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'url',
            'local',
            'name',
            'default',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
