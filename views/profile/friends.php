<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Friends');
$this->params['breadcrumbs'][] = ['label' => $user['username'], 'url' => ['profile/'.$user->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">
    <?php if($friends){ ?>
        <div class="card container">
            <ul class="list-group list-group-flush">
                <?php foreach ($friends as $item) { ?>
                    <li id="friend-item-<?= $item->friends->id ?>" class="list-group-item row">
                        <div class="col-sm-1">
                            <a href="<?= Url::to(['/profile/view/'.$item->friends->id]) ?>" style="display: block">
                                <img src="<?= $item->friends->getAvatar() ?>" class="img-circle" style="width: 100%; max-width: 75px; font-weight: bold;" alt="<?= $item->friends->username ?>">
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <?= Html::a($item->friends->username, ['/profile/view/' . $item->friends->id], ['class' => '',  'style' => 'font-weight: bold; color: #558']) ?>
                        </div>
                        <div class="col-sm-3">
                            <div class="pull-right">
                                <a href="<?= Url::toRoute(['/profile/friends/'.$item->friends->id]) ?>" class="btn btn-primary"><i class="fas fa-user-friends"></i></a>
                                <?php if($user->id == Yii::$app->user->getId()){ ?>
                                    <button onclick="removeFriend(<?= $item->friends->id ?>)" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php }else{ ?>
        <div class="container">
            <div class="alert alert-success" role="alert">
                <?= Yii::t('app', 'This user not have friends!') ?>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    function removeFriend(id) {
        $.ajax({
            url: '<?= Url::toRoute('/profile/remove-friend/', true) ?>'+'/'+id,
            type: 'post',
            data: {
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                console.log(data.message);
                document.getElementById("friend-item-"+id).classList.add("hidden");
            }
        });
    }
</script>