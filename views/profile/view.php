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
                        <h3 class="panel-title"><?= $user['username'] ?> <?= $user['online'] ? '<span class="text-success pull-right">֍</span>' : '<span class="text-danger pull-right">֍</span>' ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="card">
                            <img src="<?= $user['avatar'] ?>" alt="avatar" class="card-img-top" style="width: 100%;">
                            <div class="card-body">
                                <h1 class="card-title text-center"><?= $user['first_name'] ?> <?= $user['last_name'] ?></h1>

                                <div class="list-group">
                                    <?php if(!$user['own']){ ?>
                                        <?= $user['friend'] ? Html::a('Remove friends', '/profile/remove-friend/' . $user['id'], ['class' => 'list-group-item text-center']) : Html::a('Add to friends', '/profile/add-friend/' .  $user['id'], ['class' => 'list-group-item text-center']) ?>
                                        <?= Html::a('Write message', '/messages/' . $user['id'], ['class' => 'list-group-item text-center']) ?>
                                    <?php } ?>

                                    <?php if($user['id'] == Yii::$app->user->getId() || Yii::$app->user->can('Administrator')){ ?>
                                        <?= Html::a('Update', ['update', 'id' => $user["id"]], ['class' => 'list-group-item text-center']) ?>
                                    <?php } ?>

                                    <?= Html::a('Friends <span class="badge pull-right">' .  $user['count_friends'] .'</span>', '/profile/friends/' . $user['id'], ['class' => 'list-group-item']) ?>

                                    <?php if($user['own']){ ?>
                                        <?= Html::a('Messages <span class="badge pull-right">'.$user['not_read_message'].'</span>', '/conversation', ['class' => 'list-group-item']) ?>
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
                <li class="list-group-item">Created at: <?= date('d-m-Y', $user['created_at']) ?></li>
                <li class="list-group-item">Email: <a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></li>
            </ul>
        </div>

    </div>

    <div class="row">
        <?php if($user['count_friends']){ ?>
            <div class="col-12">
                <div class="panel panel-default panel_profile_friends">
                    <div class="panel-heading">
                        <h3 class="panel-title">Friends</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        $count = 0;
                        foreach ($user['friends'] as $item) {
                            $count++;
                            //if($count >= 7) break;
                            ?>
                            <div class="col-sm-2 col-md-1 col-lg-1 thumbnail">
                                <a href="/profile/view/<?= $item->friends->id ?>">
                                    <img src="<?= $item->friends->getAvatar() ?>" class="img-circle" alt="<?= $item->friends->username ?>">
                                </a>
                                <a href="/profile/view/<?= $item->friends->id ?>" class="btn center-block"><?= $item->friends->username ?></a>
                                <!-- <?= Html::a($item->friends->username, '/profile/view/' . $item->friends->id, ['class' => 'btn center-block']) ?> -->
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<div class="user-view">
    <?php /* DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'dateFormat' => 'MM/dd/yyyy',
            'datetimeFormat' => 'dd/MM/yyyy HH:mm:ss',
        ],
        'attributes' => [
            [
                'attribute' => 'avatar',
                'format' => 'raw',
                'value' => function($data){
                    return '<img src="' . $data->getAvatar() .'" alt="avatar" style="max-width: 200px;max-height: 200px;">';
                }
            ],
            'first_name',
            'last_name',
            'email:email',
            'created_at:datetime',
            [
                'attribute' => 'online',
                'format' => 'raw',
                'value' => function($data){
                    return $data->isOnline($data->id) ? '<span class="text-success">Online</span>' : '<span class="text-danger">Offline</span>';
                }
            ],
            [
                'attribute' => 'Rules',
                'format' => 'raw',
                'value' => function($model){
                    $userRules = '';
                    $userAssigned = Yii::$app->authManager->getAssignments($model->id);

                    foreach($userAssigned as $userAssign){
                        $userRules .= $userAssign->roleName . ', ';
                    }

                    return substr($userRules,0,-2);
                }
            ],
        ],
    ])*/ ?>

</div>
