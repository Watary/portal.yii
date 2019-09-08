<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Galleries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-galleries-index">
    <p>
        <?= Html::a('Create Gallery Galleries', ['create'], ['class' => 'btn btn-default pull-right']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'dateFormat' => 'MM/dd/yyyy',
            'datetimeFormat' => 'dd-MM-yyyy HH:mm:ss',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'description:ntext',
            'alias',
            [
                'attribute' => 'id_owner',
                'label'=>'Owner',
                'format' => 'raw',
                'value' => function($data){
                    return $data->owner->username;
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
            [
                'label'=>'Actions',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['galleries/update/'.$data->alias]);
                }

            ],
        ],
    ]); ?>
</div>
