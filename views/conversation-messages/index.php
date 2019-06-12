<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

if(!$conversation_title){
    $conversation_title = Yii::t('app', 'Not name');
}

$this->title = $conversation_title;
$this->params['breadcrumbs'][] = $this->title;

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
            <?= Yii::t('app', 'More') ?> ...
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownButton">
            <button onclick="deleteMessage()" type="button" class="dropdown-item"><?= Yii::t('app', 'Delete messages') ?></button>
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#rename_conversation"><?= Yii::t('app', 'Rename conversation') ?></button>
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#image_conversation"><?= Yii::t('app', 'Image conversation') ?></button>
            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#leave_conversation"><?= Yii::t('app', 'Leave this conversation') ?></button>
            <!-- <button type="button" class="dropdown-item" data-toggle="modal" data-target="#participants">< ?= Yii::t('app', 'Participants') ?></button> -->
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
            url: '<?= Url::toRoute('/conversation-messages/view/'.$id_conversation, true) ?>',
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
            url: '<?= Url::toRoute('/conversation-messages/view-new-message/'.$id_conversation, true) ?>',
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

    //if(countMessages) {
        setTimeout(showOldMessage, 500);
        setTimeout(showOldMessage, 100);
    //}
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
                <h3 class="modal-title"><?= Yii::t('app', 'Rename conversation')?></h3>
            </div>

            <div class="modal-body">
                <label for="conversation-title"><?= Yii::t('app', 'Conversation title')?>:</label>
                <div id="conversation-title" contenteditable="true" style="border: 1px solid #919dbb; border-radius: 5px; min-height: 35px; width: 100%; box-shadow: 0px 0px 15px -7px #021751 inset;padding: 5px;margin-bottom: 10px;"><?= $conversation_title ?></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'Close')?></button>
                <button onclick="renameConversation()" type="button" class="btn btn-primary" data-dismiss="modal"><?= Yii::t('app', 'Rename')?></button>
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
                <h3 class="modal-title"><?= Yii::t('app', 'Image conversation')?></h3>
            </div>

            <div class="modal-body">
                <div style="position:relative;">
                    <label for="image-conversation" class='btn btn-primary' href='javascript:;'>
                        <span style="cursor: pointer"><?= Yii::t('app', 'Choose File')?>...</span>
                        <input id="image-conversation" type="file" style='display: none;cursor: pointer;position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
                    </label>
                    &nbsp;
                    <span class='label label-info' id="upload-file-info"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'Close')?></button>
                <button id="image-conversation-button" type="button" class="btn btn-primary" data-dismiss="modal"><?= Yii::t('app', 'Save')?></button>
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
            url         : url_upload,
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
$this->registerJsVar('url_upload',  Url::toRoute('/conversation-messages/upload/'.$id_conversation, true));
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
                <h3 class="modal-title"><?= Yii::t('app', 'Leave this conversation')?></h3>
            </div>

            <div class="modal-body">
                <?= Yii::t('app', 'Are you sure you want to leave this conversation?')?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'NO')?></button>
                <button id="leave-conversation-button" type="button" class="btn btn-primary" data-dismiss="modal"><?= Yii::t('app', 'YES')?></button>
            </div>
        </div>
    </div>
</div>

<?php
$script =  <<< JS
    $('#leave-conversation-button').on( 'click', function( event ){
        $.ajax({
            url         : url_leave,
            type        : 'POST',
            data        : {
                id_conversation: id_conversation,
            },
            success: function (data) {
                console.log(data.message);
                $('#leave_conversation').modal('hide');
                $('#message-text').remove();
            }    
        });
    
    });
JS;
$this->registerJsVar('id_conversation',  $id_conversation);
$this->registerJsVar('url_leave',  Url::toRoute('/conversation-participant/leave', true));
$this->registerJs($script);
?>
<!-- Modal "Leave conversation" END -->

<!-- Modal "participants" BEGIN -->
<div class="modal fade" id="participants" tabindex="-1" role="dialog" aria-labelledby="Participants" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title"><?= Yii::t('app', 'Participants')?></h3>
            </div>

            <div class="modal-body">
                <label for="conversation-title"><?= Yii::t('app', 'Conversation title:') ?></label>
                <div id="conversation-title" contenteditable="true" style="border: 1px solid #919dbb; border-radius: 5px; min-height: 35px; width: 100%; box-shadow: 0px 0px 15px -7px #021751 inset;padding: 5px;margin-bottom: 10px;"></div>

                <ul class="list-group">
                    <?php foreach ($participant_list as $item) { ?>
                        <li class="list-group-item" onclick="selectFriend(this, <?= $item['id'] ?>)">
                            <img src="<?= $item['avatar'] ?>" class="img-circle" alt="<?= $item['username'] ?>" style="max-width: 50px">
                            <?= $item['username'] ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'Close')?></button>
                <button id="save-participant-button" type="button" class="btn btn-primary" data-dismiss="modal"><?= Yii::t('app', 'Save')?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var selectParticipantList = {};

    function selectFriend(element, id){
        if(selectParticipantList[id]){
            element.classList.remove("select-message");
            delete selectParticipantList[id];
        }else{
            element.classList.add("select-message");
            selectParticipantList[id] = true;
        }
    }
</script>
<?php
$script =  <<< JS
     $('#save-participant-button').on( 'click', function( event ){
         console.log(selectParticipantList);
        /*$.ajax({
            url         : url_participant,
            type        : 'POST',
            data        : {
                id_conversation: id,
            },
            success: function (data) {
                console.log(data.message);
                $('#leave_conversation').modal('hide');
                $('#message-text').remove();
            }    
        });*/
    
    });
JS;
$this->registerJsVar('id_conversation',  $id_conversation);
$this->registerJsVar('url_participant',  Url::toRoute('/conversation-participant/participant_save', true));
$this->registerJs($script);
?>
<!-- Modal "participants" END -->
