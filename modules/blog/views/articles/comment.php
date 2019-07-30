<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

?>
<div class="comments">
    <?php foreach ($comments as $comment){ ?>
        <div class="media-comments">
            <img src="/<?= $comment->owner->avatar ?>" style="max-width: 64px;max-height: 64px;margin-right: 15px;" class="mr-3" alt="...">
            <div class="media-body">
                <h4 class="mt-0"><?= $comment->owner->username ?> <span class="badge badge-secondary"><?= date("d.m.Y H:i:s",(integer) $comment->created_at) ?></span></h4>
                <?= $comment->text ?>
            </div>
        </div>
    <?php } ?>
</div>


