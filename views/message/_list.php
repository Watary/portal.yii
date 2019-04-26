<?php


use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\db\ActiveQuery $messagesQuery
 */
?>
<?= yii\widgets\ListView::widget([
    'itemView' => '_row',
    'layout' => '{items}',
    'viewParams' => [
        'userFrom' => $userFrom,
        'userTo' => $userTo,
    ],
    'dataProvider' => new yii\data\ActiveDataProvider([
        'query' => $messagesQuery,
        'pagination' => [
            'pageSize' => 10,
            'page' => 2,
        ],
    ]),
]) ?>