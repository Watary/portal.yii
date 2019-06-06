<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversation Messages';
$this->params['breadcrumbs'][] = $this->title;

if(!$conversation_title){
    $conversation_title = 'Not name';
}
?>
<style>
    .conversation-top{
        position: sticky;
        top: 50px;
        background-color: rgba(212, 212, 212, 0.91);
        z-index: 100;
        border-radius: 3px;
        border: 1px solid rgba(0, 0, 0, 0.15);
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
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#image_conversation">Image conversation</button>
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#leave_conversation">Leave this conversation</button>
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
            url: '<?= Url::toRoute('/conversation-messages/remove', true) ?>',
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
            url: '<?= Url::toRoute('/messages/rename', true) ?>',
            type: 'post',
            data: {
                title: title.textContent,
                id_conversation: <?= $id_conversation ?>,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                //console.log(data.message);
                show_title.innerHTML = data.message;
                $('#rename_conversation').modal('hide');
            }
        });
    }
</script>

<!-- Modal "Rename conversation" BEGIN -->
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
<!-- Modal "Rename conversation" END -->

<!-- Modal "Image conversation" BEGIN -->
<div class="modal fade" id="image_conversation" tabindex="-1" role="dialog" aria-labelledby="Image conversation" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Image conversation</h3>
            </div>

            <div class="modal-body">
                <div style="position:relative;">
                    <label for="image-conversation" class='btn btn-primary' href='javascript:;'>
                        <span style="cursor: pointer">Choose File...</span>
                        <input id="image-conversation" type="file" style='display: none;cursor: pointer;position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
                    </label>
                    &nbsp;
                    <span class='label label-info' id="upload-file-info"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="image-conversation-button" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>

<?php
$script =  <<< JS
    var files; // переменная. будет содержать данные файлов
    
    // заполняем переменную данными, при изменении значения поля file 
    $('input[type=file]').on('change', function(){
        files = this.files;
    });
    
    $('#image-conversation-button').on( 'click', function( event ){
        event.stopPropagation(); // остановка всех текущих JS событий
    
        // ничего не делаем если files пустой
        if( typeof files == 'undefined' ) return;
    
        // создадим объект данных формы
        var data = new FormData();
    
        // заполняем объект данных файлами в подходящем для отправки формате
        $.each( files, function( key, value ){
            data.append( key, value );
        });
    
        // добавим переменную для идентификации запроса
        data.append( 'image_upload', 1 );
        
        // AJAX запрос
        $.ajax({
            url         : url,
            type        : 'POST',
            data        : data,
            cache       : false,
            dataType    : 'json',
            processData : false,
            contentType : false, 
            success: function (data) {
                console.log(data.message);
                $('#image_conversation').modal('hide');
            }    
        });
    
    });
JS;
$this->registerJsVar('url',  Url::toRoute('/conversation-messages/upload/'.$id_conversation, true));
$this->registerJs($script);
?>
<!-- Modal "Image conversation" END -->

<!-- Modal "Leave conversation" BEGIN -->
<div class="modal fade" id="leave_conversation" tabindex="-1" role="dialog" aria-labelledby="Leave this conversation" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Leave this conversation</h3>
            </div>

            <div class="modal-body">
                Ви впевнені, що хочете покинути цю бесіду?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                <button id="leave-conversation-button" type="button" class="btn btn-primary" data-dismiss="modal">YES</button>
            </div>
        </div>
    </div>
</div>

<?php
$script =  <<< JS
    $('#leave-conversation-button').on( 'click', function( event ){
        $.ajax({
            url         : url,
            type        : 'POST',
            data        : {
                id: id,
            },
            success: function (data) {
                console.log(data.message);
                $('#leave_conversation').modal('hide');
                $('#message-text').remove();
            }    
        });
    
    });
JS;
$this->registerJsVar('id',  $id_conversation);
$this->registerJsVar('url',  Url::toRoute('/conversation-participant/leave', true));
$this->registerJs($script);
?>
<!-- Modal "Leave conversation" END -->