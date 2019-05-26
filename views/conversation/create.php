<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Conversation */

$this->title = 'Create Conversation';
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <div class="panel-body">
        <?php
        foreach ($friends as $item) { ?>
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
