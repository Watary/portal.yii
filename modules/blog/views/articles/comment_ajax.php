<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

?>
<div class="comments" id="comments-list"></div>

<script>
    function findComments(){
        id_comments = null;
        $.ajax({
            url: '<?= Url::toRoute('/blog/comments/find-comments', true) ?>',
            type: 'post',
            data: {
                article: <?= $id_article ?>,
                id_comments: id_comments,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                //console.log(data);
                if(data.list_comments){
                    document.getElementById("comments-list").innerHTML = data.list_comments;
                }else{
                    document.getElementById("comments-list").innerHTML = '<div class="alert alert-success" role="alert">Ця стаття не має коментарів</div>';
                }
            }
        });
    }

    setTimeout(findComments, 1000);
</script>


