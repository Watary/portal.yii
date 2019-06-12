<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Conversations');
$this->params['breadcrumbs'][] =  $this->title;


?>
<style>
    .conversation{
        border: rgba(0,114,144,0.08) solid 1px;
        border-radius: 5px;
        margin-bottom: 3px;
        padding: 5px;
    }
    .conversation:hover{
        background-color: rgba(0, 0, 0, 0.05);
    }
</style>
<div class="conversation-index">

    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#create_conversation"><?= Yii::t('app', 'Create conversation') ?></button>
    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <?php foreach ($listConversation as $key => $item){
            $url_conversation = Url::toRoute(['/messages/'.$item[0]['id_conversation']]);
            ?>
            <a href="<?= $url_conversation ?>">
                <div class="col-sm-12 conversation clearfix">
                    <div class="col-sm-1">
                        <?php if($item['conversation']->image){ ?>
                            <img src="<?= Url::to($item['conversation']->image, true) ?>" style="width: 100%;max-width: 100px;margin: 0 auto;display: block">
                        <?php }else{ ?>
                            <i class="fa fa-users" style="width: 100%;font-size: 45px;text-align: center"></i>
                        <?php } ?>
                    </div>
                    <div class="col-sm-11">
                        <div>
                            <span style="font-weight: bold">
                                <?= $item['conversation']->title ?>
                                <?php if($item['count_not_read']){ ?>
                                    <span class="badge"><?= $item['count_not_read'] ?></span>
                                <?php } ?>
                            </span>
                            <span class="pull-right"><?= date("d.m.Y H:i:s",(integer)$item['message']->date) ?></span>
                        </div>
                        <div><?= $item['message']->text ?></div>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>