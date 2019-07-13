<?php
use yii\helpers\Url;

/**
 * @var string $title
 * @var int $article
*/

?>
<div class="blog-administration-widget">
    <div class="list-group">
        <span class="list-group-item list-group-item-action header">
            <?= $title ?>
        </span>
        <a href="<?= Url::to(['/blog/articles/update/'.$article]) ?>" class="list-group-item list-group-item-action"><?= Yii::t('app', 'Edit') ?></a>
        <a href="<?= Url::to(['/blog/articles/delete/'.$article]) ?>" class="list-group-item list-group-item-action"><?= Yii::t('app', 'Delete') ?></a>
    </div>
</div>