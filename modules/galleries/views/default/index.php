<?php

use yii\helpers\Url;

$this->title = "Galleries";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="galleries-default-index">
    <div class="row">
        <?php foreach ($model as $item) { ?>
            <div class="card col-md-3" style="margin-bottom: 20px;">
                <img src="/files/galleries/gallery_<?= $item->id ?>/<?= $item->images[0]->path ?>" class="card-img-top" style="width: 100%" alt="<?= $item->title ?>">
                <span class="badge badge-secondary" style="position: absolute; right: 25px;top: 10px;"><?= count($item->images) ?></span>
                <div class="card-body">
                    <h5 class="card-title" style="text-align: center"><?= $item->title ?></h5>
                    <p class="card-text"><?= $item->description ?></p>
                    <a href="<?= Url::to(['/galleries/gallery/'.$item->alias]) ?>" class="btn btn-block btn-default">Open gallery</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
