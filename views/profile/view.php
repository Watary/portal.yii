<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $user['username'];
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => Yii::$app->user->identity->getId()];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $user['username'] ?> <span id="user-online-show" class="text-danger pull-right">֍</span></h3>
                    </div>
                    <div class="panel-body">
                        <div class="card">
                            <img src="<?= $user['avatar'] ?>" alt="avatar" class="card-img-top" style="width: 100%;">
                            <div class="card-body">
                                <h1 class="card-title text-center"><?= $user['first_name'] ?> <?= $user['last_name'] ?></h1>

                                <div class="list-group">
                                    <?php if(!$user['own']){ ?>
                                        <?= $user['friend'] ? Html::a(Yii::t('app', 'Remove friends'), ['/profile/remove-friend/' . $user['id']], ['class' => 'list-group-item text-center']) : Html::a(Yii::t('app', 'Add to friends'), ['/profile/add-friend/' .  $user['id']], ['class' => 'list-group-item text-center']) ?>
                                        <?= Html::a(Yii::t('app', 'Write message'), ['/messages/' . $user['id']], ['class' => 'list-group-item text-center']) ?>
                                    <?php } ?>

                                    <?php if($user['id'] == Yii::$app->user->getId() || Yii::$app->user->can('Administrator')){ ?>
                                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $user["id"]], ['class' => 'list-group-item text-center']) ?>
                                    <?php } ?>

                                    <?= Html::a(Yii::t('app', 'Friends').' <span class="badge pull-right">' .  $user['count_friends'] .'</span>', ['/profile/friends/' . $user['id']], ['class' => 'list-group-item']) ?>

                                    <?php if($user['own']){ ?>
                                        <?= Html::a(Yii::t('app', 'Messages').' <span class="badge pull-right">'.$user['not_read_message'].'</span>', ['/conversation'], ['class' => 'list-group-item']) ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <ul class="list-group">
                <li class="list-group-item"><?= Yii::t('app', 'Created at:') ?> <?= date('d-m-Y', $user['created_at']) ?></li>
                <li class="list-group-item"><?= Yii::t('app', 'Email:') ?> <a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></li>
            </ul>
        </div>

    </div>

    <div class="row">
        <?php if($user['count_friends']){ ?>
            <div class="col-12">
                <div class="panel panel-default panel_profile_friends">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= Yii::t('app', 'Friends') ?></h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        $count = 0;
                        foreach ($user['friends'] as $item) {
                            $count++;
                            //if($count >= 7) break;
                            ?>
                            <div class="col-sm-2 col-md-1 col-lg-1 thumbnail">
                                <a href="<?= Url::to(['/profile/view/'.$item->friends->id]) ?>">
                                    <img src="<?= $item->friends->getAvatar() ?>" class="img-circle" alt="<?= $item->friends->username ?>">
                                </a>
                                <?= Html::a($item->friends->username, ['/profile/view/' . $item->friends->id], ['class' => 'btn center-block']) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<?php
$script =  <<< JS
    function isOnline(){
        var user_online_show = document.getElementById('user-online-show');
        
        $.ajax({
            url         : url,
            type        : 'POST',
            data        : {
                user_id: id,
                _csrf: _csrf_get
            },
            success: function (data) {
                console.log(data.message);
                if(data.message){
                    user_online_show.classList.remove("text-danger");
                    user_online_show.classList.add("text-success");
                }else{
                    user_online_show.classList.remove("text-success");
                    user_online_show.classList.add("text-danger");
                }                
            }    
        });
    }
    setTimeout(isOnline, 500);
    setInterval(isOnline, 5000);
JS;
$this->registerJsVar('_csrf_get',  Yii::$app->request->getCsrfToken());
$this->registerJsVar('id',  $user['id']);
$this->registerJsVar('url',  Url::toRoute('/profile/is-online/', true));
$this->registerJs($script);
?>
