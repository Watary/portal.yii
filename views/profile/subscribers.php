<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Subscribers');
$this->params['breadcrumbs'][] = ['label' => $user['username'], 'url' => ['profile/'.$user->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="row">
    <?php if($friends){ ?>
        <div class="card container">
            <ul class="list-group list-group-flush">
                <?php foreach ($friends as $item) { ?>
                    <li id="friend-item-<?= $item->user->id ?>" class="list-group-item clearfix">
                        <div class="row">
                            <div class="col-sm-1">
                                <a href="<?= Url::to(['/profile/view/'.$item->id]) ?>" style="display: block">
                                    <img src="<?= $item->user->getAvatar() ?>" class="img-circle" style="width: 100%; max-width: 75px; font-weight: bold;" alt="<?= $item->user->username ?>">
                                </a>
                            </div>
                            <div class="col-sm-8">
                                <?= Html::a($item->user->username, ['/profile/view/' . $item->user->id], ['class' => '',  'style' => 'font-weight: bold; color: #558']) ?>
                            </div>
                            <div class="col-sm-3">
                                <div class="pull-right">
                                    <button onclick="addFriend(<?= $item->user->id ?>)" type="button" class="btn btn-success"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php }else{ ?>
        <div class="container">
            <div class="alert alert-success" role="alert">
                <?= Yii::t('app', 'This user not have request friends!') ?>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    function addFriend(id) {
        $.ajax({
            url: '<?= Url::toRoute('/profile/ajax-add-friend/', true) ?>',
            type: 'post',
            data: {
                id: id,
                _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
            },
            success: function (data) {
                //console.log(data.message);
                document.getElementById("friend-item-"+id).classList.add("hidden");
            }
        });
    }
</script>