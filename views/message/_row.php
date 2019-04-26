<?php
/**
 * @var yii\web\View $this
 * @var app\models\Message $model
 */
?>

<div class="row">
    <div class="col-md-3">
        <?php
            if($userFrom->id == $model->id_from){
                echo $userFrom->username;
            }else{
                echo $userTo->username;
            }
        ?>
    </div>
    <div class="col-md-9"><?= $model->text ?></div>
    <hr>
</div>