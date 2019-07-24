<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogCategories */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['/blog']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['category-alias'] = $model->alias ? $model->alias : 'uncategorized';
\yii\web\YiiAsset::register($this);
?>
<div class="blog">
    <h1><?= $this->title  ?></h1>

    <div class="category-description">
        <?= $model->description  ?>
    </div>

    <?php foreach ($articles as $article) {?>
        <article class="blog-article" style="">
            <?php if($article->image){ ?>
                <div class="image" style="background-image: url('<?= $article->image ?>')"></div>
            <?php } ?>
            <div class="article-body">
                    <span class="article-info">
                        <span><i class="fas fa-user"></i> <a href="<?= Url::to(['/profile/'.$article->author->id]) ?>"><?= $article->author->username ?></a></span>
                        <span><i class="fas fa-server"></i>
                            <?php if($article->category->title){ ?>
                                <a href="<?= Url::to(['/blog/categories/view/'.$article->category->alias]) ?>"><?= $article->category->title ?></a>
                            <?php }else{ ?>
                                <a href="<?= Url::to(['/blog/categories/view/uncategorized']) ?>">Uncategorized</a>
                            <?php } ?>
                        </span>
                        <span><i class="far fa-calendar-alt"></i> <?= date('d-m-Y | H:m', $article->created_at) ?></span>
                        <?php if($article->articletag){ ?>
                            <span>
                                <i class="fas fa-tags"></i>
                                <?php foreach ($article->articletag as $item) { ?>
                                    <a href="<?= Url::to(['/blog/tags/view/'.$item->tag->alias]) ?>"><?= $item->tag->title ?></a>
                                <?php } ?>
                            </span>
                        <?php } ?>
                        <span class="badge badge-secondary"><i class="far fa-comment-dots"></i> <?= $article->count_comments ?></span>
                        <span class="badge badge-secondary"><i class="fas fa-eye"></i> <?= $article->count_show ?></span>
                        <span class="badge badge-secondary"><i class="fas fa-eye"></i> <?= $article->count_show_all ?></span>
                    </span>

                <div class="rating">
                    <div class="rating-box">
                        <div class="background-box"> </div>
                        <div id="slider-box" class="slider-box" style="width: <?= $article->mark*10 ?>%"> </div>
                        <div id="slider-select-box" class="slider-select-box"> </div>
                        <div class="image-box">
                            <?php for($i = 1; $i <= 10; $i++){ ?><div id="star-<?= $i ?>" class="image" style="background-image: url('<?= '/image/design/star.png' ?>')"></div><?php } ?>
                        </div>
                    </div>
                </div>

                <h2 class="article-title"><?= $article->title ?></h2>
                <p class="article-text"><?= $article->excerpt ?></p>
            </div>
            <a href="<?= Url::to(['/blog/article/'.$article->alias]) ?>" class="btn btn-lg btn-block">Show more</a>
        </article>
    <?php } ?>
</div>

<?php if($count_pages > 1) {
    echo $this->render('/pagination/pagination',[
        'count_pages' => $count_pages,
        'page' => $page,
        'url' => 'blog/category/'.$model->alias,
    ]);
}
?>
