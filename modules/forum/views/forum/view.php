<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumForums */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Forum', 'url' => ['/forum']];
for($i = 0; $i < count($breadcrumb_list)-1; $i++){
    $this->params['breadcrumbs'][] = ['label' => $breadcrumb_list[$i]['title'], 'url' => ['/forum/'.$breadcrumb_list[$i]['id']]];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forum-forums-view">

    <h1 class="clearfix">
        <?= Html::encode($this->title) ?>
        <span class="pull-right">
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-default',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <?php if($model->close != 1){ ?>
                <?= Html::a(Yii::t('app', 'Create forum'), ['create/'.$model->id], ['class' => 'btn btn-default']) ?>
                <?= Html::a(Yii::t('app', 'Create topic'), ['topic/create/'.$model->id], ['class' => 'btn btn-default']) ?>
            <?php } ?>
        </span>
    </h1>

    <?php if($list_forums){ ?>
    <div class="list-forums">
        <div class="head"><?= Yii::t('app', 'Forums') ?></div>
        <div class="list">
            <?php foreach ($list_forums as $item){ ?>
                <div class="media-forum" id="forum-'<?= $item->id ?>">
                    <img src="/image/design/notepad.png" class="mr-3" alt="...">
                    <div class="media-body">
                        <h4 class="mt-0"><a href="<?= Url::to('forum/'.$item->id, true) ?>"><?= $item->title ?></a></h4>
                    </div>
                    <div class="forum-statistic"><?= $item->count_forums ?> - <?= $item->count_topics ?> - <?= $item->count_posts ?></div>
                    <?php if($item->lastpost){ ?>
                        <div class="forum-last-post">
                            <?= date('d-m-Y | H:i:s', $item->lastpost->created_at) ?><br>
                            <?= $item->lastpost->parent->title ?><br>
                            <?= $item->lastpost->owner->username ?>
                        </div>
                    <?php }else{ ?>
                        <div class="forum-last-post"> </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <?php if($list_topics){ ?>
    <div class="list-forums">
        <div class="head"><?= Yii::t('app', 'Topics') ?></div>
        <div class="list">
            <?php foreach ($list_topics as $item){ ?>
                <div class="media-forum" id="forum-'<?= $item->id ?>">
                    <img src="/image/design/forum-topic.png" class="mr-3" alt="...">
                    <div class="media-body">
                        <h4 class="mt-0"><a href="<?= Url::to('forum/topic/'.$item->id, true) ?>"><?= $item->title ?></a></h4>
                    </div>
                    <div class="forum-statistic"><?= $item->count_posts ?></div>
                    <?php if($item->lastpost){ ?>
                        <div class="forum-last-post">
                            <?= date('d-m-Y | H:i:s', $item->lastpost->created_at) ?><br>
                            <?= $item->lastpost->owner->username ?>
                        </div>
                    <?php }else{ ?>
                        <div class="forum-last-post"> </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

</div>
