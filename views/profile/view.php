<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <div class="row">
        <div class="col-md-6"><h1><?= Html::encode($this->title) ?></h1></div>
        <?php if($model->id == Yii::$app->user->getId() || Yii::$app->user->can('Administrator')){ ?>
        <div class="col-md-6 text-right"><?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></div>
        <?php } ?>
    </div>

    <?= DetailView::widget([
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
    ]) ?>

</div>
