<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumForums */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forum-forums-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_parent',
            'title',
            'description:ntext',
            'alias',
            'id_owner',
            'close',
            'hot',
            'count_forums',
            'count_topics',
            'count_posts',
            'id_deleted',
            'deleted_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
