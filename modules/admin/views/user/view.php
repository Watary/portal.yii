<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'dateFormat' => 'MM/dd/yyyy',
            'datetimeFormat' => 'dd/MM/yyyy HH:mm:ss',
        ],
        'attributes' => [
            'id',
            'username',
            'first_name',
            'last_name',
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
            'email:email',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data){
                    return $data->status ? '<span class="text-success">Активний</span>' : '<span class="text-danger">Не активний</span>';
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
            'online:datetime',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'avatar',
        ],
    ]) ?>

</div>
