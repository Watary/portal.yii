<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversations';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="conversation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <a class="btn btn-primary" href="<?= Url::toRoute(['create']) ?>">Create conversation</a> -->

    <div>
        <?php foreach ($listConversation as $key => $item){
            $url_conversation = Url::toRoute(['/messages/'.$item[0]['id_conversation']]);
            ?>
            <div class="col-sm-12" style="border: rgba(0,114,144,0.08) solid 1px; border-radius: 5px; margin-bottom: 3px; padding: 5px;">
                <div class="col-sm-1">
                    <?php if($item['conversation']->image){ ?>
                        <img src="<?= $item['conversation']->image ?>" style="width: 100%;">
                    <?php }else{ ?>
                        <i class="fa fa-users" style="width: 100%;font-size: 45px;text-align: center"></i>
                    <?php } ?>
                </div>
                <div class="col-sm-11">
                    <div><span style="font-weight: bold"><a href="<?= $url_conversation ?>"><?= $item['conversation']->title ?></a></span><span class="pull-right"><?= date("d.m.Y H:i:s",(integer)$item['message']->date) ?></span></div>
                    <div><a href="<?= $url_conversation ?>"><?= $item['message']->text ?></a></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
