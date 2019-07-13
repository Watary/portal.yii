<?php
use yii\helpers\Url;

/**
 * @var string $title
 * @var array $categories
*/

?>
<div class="blog-categories-widget">
    <div class="list-group">
        <span class="list-group-item list-group-item-action header">
            <?= $title ?>
        </span>
        <?php foreach ($categories as $category){ ?>
            <a href="<?= Url::to(['/blog/categories/view/'.$category->alias]) ?>" class="list-group-item list-group-item-action"><?= $category->title ?></a>
        <?php } ?>
    </div>
</div>