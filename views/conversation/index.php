<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Conversation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_owner',
            'dialog',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Бесіда</th>
                <th>Останнє повідомлення</th>
                <th>Вступив</th>
                <th>Вийшов</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listConversation as $key => $item){ ?>
                <?php foreach ($item as $key_in => $item_in){ ?>
                <tr>
                    <?php if ($key_in == 0) {?><td rowspan="<?= count($item) ?>"><?= $item_in['id_conversation'] ?></td><?php } ?>
                    <td><?= $item_in['id_last_see'] ?></td>
                    <td><?= $item_in['date_entry'] ?></td>
                    <td><?= $item_in['date_exit'] ?></td>
                </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
