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
                <div id="conversation-<?= $item['conversation']->id ?>" class="col-sm-12 conversation clearfix" style="padding: 0">
                    <a href="<?= $url_conversation ?>" class="clearfix" style="display: block; padding: 5px;">
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
                                <span class="pull-right badge badge-pill badge-light"><?= date("d.m.Y | H:i:s",(integer)$item['date']) ?></span>
                            </div>
                            <div>
                                <?php if($item['message']->text){ ?>
                                    <?= $item['message']->text ?>
                                <?php }else{ ?>
                                    <span class="badge badge-pill badge-info"><?= Yii::t('app', 'No messages') ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                </div>
        <?php } ?>
    </div>
</div>

<!-- Modal "Create conversation" BEGIN -->
<div class="modal fade" id="create_conversation" tabindex="-1" role="dialog" aria-labelledby="Create conversation" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Create conversation</h3>
            </div>

            <div class="modal-body">
                <label for="conversation-title">Conversation title:</label>
                <div id="conversation-title" contenteditable="true" style="border: 1px solid #919dbb; border-radius: 5px; min-height: 35px; width: 100%; box-shadow: 0px 0px 15px -7px #021751 inset;padding: 5px;margin-bottom: 10px;"></div>

                <ul class="list-group">
                    <?php foreach ($friends as $item) { ?>
                        <li class="list-group-item" onclick="selectFriend(this, <?= $item->friends->id ?>)">
                            <img src="<?= $item->friends->getAvatar() ?>" class="img-circle" alt="<?= $item->friends->username ?>" style="max-width: 50px">
                            <?= $item->friends->username ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button onclick="createConversation()" type="button" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>

<script>
    var title = document.getElementById('conversation-title');
    var selectList = {};

    function createConversation(){
        $.ajax({
            url: 'http://portal.yii/conversation/create',
            type: 'post',
            data: {
                title: title.textContent,
                selectList: selectList,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                console.log(data.message);
            }
        });
    }

    function selectFriend(element, id){
        if(selectList[id]){
            element.classList.remove("select-message");
            delete selectList[id];
        }else{
            element.classList.add("select-message");
            selectList[id] = true;
        }
    }
</script>
<!-- Modal "Create conversation" END -->