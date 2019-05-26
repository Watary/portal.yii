<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversation Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .conversation-top{
        position: sticky;
        top: 50px;
        background-color: rgba(212, 212, 212, 0.91);
        z-index: 100;
        border-radius: 3px;
    }
        .conversation-top .title{
            text-align: center;
            font-size: 24px;
        }
        .conversation-top .dropdown{
        }
        .conversation-top .back, .conversation-top .back a{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 1.42857143;
            color: #333333;
            border-radius: 3px;
            background-color: rgba(246, 246, 246, 0.88);
            font-size: 18px;
            text-decoration: none;
            padding: 4px;
        }
</style>
<div class="conversation-top">
    <div class="back pull-left"><a href="<?= Url::toRoute(['/conversation']) ?>">←</a></div>

    <div class="dropdown pull-right">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            More ...
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownButton">
            <button onclick="deleteMessage()" type="button" class="dropdown-item">Delete messages</button>
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#rename_conversation">Rename conversation</button>
        </div>
    </div>

    <div id="show-title" class="title"><?= $conversation_title ?></div>
</div>

<div class="conversation-messages-index">

    <div class="message-index">


        <div id="message-list" class="message-list"></div>

        <?php if($participant_now){
            echo $this->render('_form_div', [
                'model' => $model,
                'id_conversation' => $id_conversation,
            ]);
        } ?>

    </div>

</div>


<script>

    var countMessages = <?= $countMessages ?>;
    var lastIdMessage = <?= $lastIdMessage ?>;
    var countShow = 10;
    var startShow;
    var checkScroll = true;

    if(countMessages < countShow){
        startShow = 0;
    } else {
        startShow = countMessages - countShow + 1;
    }

    function showBeforeMessages(data){
        var list = document.getElementById("message-list");
        var node = document.createElement("div");
        node.innerHTML = data;
        list.insertBefore(node, list.children[0]);

        scrollBy(0, document.getElementById("message-list").children[0].offsetHeight+5);
        checkScroll = true;
    }

    function showAfterMessages(data){
        var list = document.getElementById("message-list");
        var node = document.createElement("div");
        var scroll = false;

        if((window.scrollY + 10) >= (document.body.scrollHeight - document.body.clientHeight)) {
            scroll = true;
        }

        node.innerHTML = data;
        list.append(node);

        if(data && scroll) {
            scrollTo(0, document.body.scrollHeight);
        }

    }

    function showOldMessage() {
        if(countMessages <= 0){
            return;
        }

        countMessages -= countShow;

        $.ajax({
            url: 'http://portal.yii/conversation-messages/view/<?= $id_conversation ?>',
            type: 'post',
            data: {
                startShow: startShow,
                countShow: countShow,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                //console.log(data.message);
                showBeforeMessages(data.message);
            }
        });

        if(startShow < countShow){
            startShow = 0;
            countShow = countMessages;
        } else {
            startShow -= countShow;
        }
    }

    function showNewMessage() {
        $.ajax({
            url: 'http://portal.yii/conversation-messages/view-new-message/<?= $id_conversation ?>',
            type: 'post',
            data: {
                id_conversation: <?= $id_conversation ?>,
                lastIdMessage: lastIdMessage,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                //console.log(data.message);
                if(data.message){
                    showAfterMessages(data.message);
                    lastIdMessage = data.lastIdMessage;
                }
            }
        });
    }

    window.onscroll = function() {
        if(window.pageYOffset < document.getElementById("message-list").offsetTop && checkScroll) {
            showOldMessage();
            checkScroll = false;
        }
    };

    setTimeout(showOldMessage, 500);
    setTimeout(showOldMessage, 1);
    setInterval(showNewMessage, 1000);
</script>

<script>
    var selectList = {};

    function selectMessage(element, id){
        if(selectList[id]){
            element.classList.remove("select-message");
            delete selectList[id];
        }else{
            element.classList.add("select-message");
            selectList[id] = true;
        }
    }

    function deleteMessage() {
        $.ajax({
            url: 'http://portal.yii/conversation-messages/remove',
            type: 'post',
            data: {
                selectList: selectList,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                console.log(data.message);

                for(var i = 0; i < data.message.length; i++){
                    document.getElementById("conversation-messages-"+data.message[i]).classList.add("hidden");
                }
            }
        });
    }

    function renameConversation(){
        var title = document.getElementById('conversation-title');
        var show_title = document.getElementById('show-title');

        $.ajax({
            url: 'http://portal.yii/messages/rename',
            type: 'post',
            data: {
                title: title.textContent,
                id_conversation: <?= $id_conversation ?>,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                //console.log(data.message);
                show_title.innerHTML = data.message;
            }
        });
    }
</script>

<!-- Modal "Rename conversation" -->
<div class="modal fade" id="rename_conversation" tabindex="-1" role="dialog" aria-labelledby="Rename conversation" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Rename conversation</h3>
            </div>

            <div class="modal-body">
                <label for="conversation-title">Conversation title:</label>
                <div id="conversation-title" contenteditable="true" style="border: 1px solid #919dbb; border-radius: 5px; min-height: 35px; width: 100%; box-shadow: 0px 0px 15px -7px #021751 inset;padding: 5px;margin-bottom: 10px;"><?= $conversation_title ?></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button onclick="renameConversation()" type="button" class="btn btn-primary" data-dismiss="modal">Rename</button>
            </div>
        </div>
    </div>
</div>