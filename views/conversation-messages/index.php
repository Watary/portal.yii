<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversation Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-messages-index">

    <div class="message-index">

        <div id="message-list" class="message-list"></div>

        <?= $this->render('_form_div', [
            'model' => $model,
            'id_conversation' => $id_conversation,
        ]) ?>

        <script>

            var countMessages = <?= $countMessages ?>;
            var lastIdMessage = <?= $lastIdMessage ?>;
            var countShow = 10;
            var startShow;
            var checkScroll = true;

            if(countMessages < countShow){
                startShow = 0;
            } else {
                startShow = countMessages - countShow;
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

            setTimeout(showOldMessage, 100);
            setInterval(showNewMessage, 1000);
        </script>

        <script>
            var selectList = [];

            function selectMessage(element, id){
                if(selectList[id]){
                    element.classList.remove("select-message");
                    selectList[id] = false;
                }else{
                    element.classList.add("select-message");
                    selectList[id] = true;
                }
            }
        </script>

    </div>

</div>

