<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogArticles */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog article', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog-articles-view">

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

<?php /*
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_category',
            'id_author',
            'title',
            'text:ntext',
            'image:ntext',
            'alias',
            'count_show_all',
            'count_show',
            'mark',
            'created_at',
            'updated_at',
        ],
    ]) ?>
 */ ?>
    <div class="blog">
        <article class="blog-article" style="">
            <?php if($model->image){ ?>
            <div class="image" style="background-image: url('<?= Url::to([$model->image]) ?>')"></div>
            <?php } ?>
            <div class="article-body">
                    <span class="article-info">
                        <span><i class="fas fa-user"></i> <a href="<?= Url::to(['/profile/view/'.$model->id_author]) ?>"><?= $model->author->username ?></a></span>
                        <span><i class="fas fa-server"></i> <a href="<?= Url::to(['/blog/categories/view/'.$model->category->alias]) ?>"><?= $model->category->title ?></a></span>
                        <span><i class="far fa-calendar-alt"></i> <?= date('d-m-Y | H:m', $model->created_at) ?></span>
                        <?php if($model->articletag){ ?>
                            <span>
                                <i class="fas fa-tags"></i>
                                <?php foreach ($model->articletag as $item) { ?>
                                    <a href="<?= Url::to(['/blog/tags/view/'.$item->tag->alias]) ?>"><?= $item->tag->title ?></a>
                                <?php } ?>
                            </span>
                        <?php } ?>
                        <span class="badge badge-secondary"><i class="far fa-comment-dots"></i> <?= $model->count_comments ?></span>
                        <span class="badge badge-secondary"><i class="fas fa-eye"></i> <?= $model->count_show ?></span>
                        <span class="badge badge-secondary"><i class="fas fa-eye"></i> <?= $model->count_show_all ?></span>
                    </span>
                <h2 class="article-title"><?= $model->title ?></h2>
                <p class="article-text"><?= $model->text ?></p>
            </div>
        </article>
    </div>

</div>
