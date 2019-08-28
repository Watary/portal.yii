<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumTopics */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum']];
for($i = 0; $i < count($breadcrumb_list); $i++){
    $this->params['breadcrumbs'][] = ['label' => $breadcrumb_list[$i]['title'], 'url' => ['/forum/'.$breadcrumb_list[$i]['id']]];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forum-topics-view">

    <h1><?= Html::encode($this->title) ?>
        <span class="pull-right">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-default',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <?php if($model->close != 1){ ?>
                <?= Html::a('Add post', ['post/create/'.$model->id], ['class' => 'btn btn-default']) ?>
            <?php } ?>
        </span>
    </h1>

    <?php foreach ($model->posts as $item){ ?>
        <table class="topic-post">
            <tr>
                <td class="td-1"><?= $item->owner->username ?></td>
                <td><?= date('d-m-Y | H:i:s', $item->created_at) ?></td>
            </tr>
            <tr>
                <td class="td-1"><img src="/<?= $item->owner->avatar ?>" alt="avatar" class="card-img-top" style="max-width: 90%;"></td>
                <td><?= $item->text ?></td>
            </tr>
            <tr>
                <td class="td-1">
                    <?php if($model->close != 1){ ?>
                        <?= Html::a('<i class="fas fa-pen"></i>', ['post/update/'.$item->id], ['class' => 'btn btn-default forum-post-control']) ?>
                        <?= Html::a('<i class="fas fa-minus"></i>', ['post/delete/'.$item->id], [
                            'class' => 'btn btn-default forum-post-control',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php }else{ echo " "; } ?>
                </td>
                <td></td>
            </tr>
        </table>
    <?php } ?>

</div>
