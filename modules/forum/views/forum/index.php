<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Forum';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-forums-view">

    <h1><?= Html::encode($this->title) ?><?= Html::a('Create Forum', ['create'], ['class' => 'btn btn-default pull-right']) ?></h1>

    <div class="list-forums">
        <div class="head">Forums</div>
        <div class="list">
            <?php foreach ($list_forums as $item){ ?>
                <div class="media-forum" id="forum-'<?= $item->id ?>">
                    <img src="/uploads/avatar/avatar_1.jpg" class="mr-3" alt="...">
                    <div class="media-body">
                        <h4 class="mt-0"><a href="<?= Url::to('forum/'.$item->id, true) ?>"><?= $item->title ?></a></h4>
                    </div>
                    <div><?= $item->count_forums ?></div>
                    -
                    <div><?= $item->count_topics ?></div>
                    -
                    <div><?= $item->count_posts ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
