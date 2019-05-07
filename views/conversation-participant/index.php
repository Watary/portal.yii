<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversation Participants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-participant-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Conversation Participant', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_conversation',
            'id_user',
            'id_last_see',
            'date_entry',
            //'date_exit',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
