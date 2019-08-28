<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\blog\models\BlogArticles */

$this->params['article-id'] = $model->id;


if($model->category->alias) {
    $this->params['category-alias'] = $model->category->alias;
    $breadcrumbs_title =  $model->category->title;
    $breadcrumbs_url =  $model->category->alias;
}else{
    $this->params['category-alias'] = 'uncategorized';
    $breadcrumbs_title =  'Uncategorized';
    $breadcrumbs_url =  'uncategorized';
}


$this->params['widget']['article'] = $model;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['/blog']];
$this->params['breadcrumbs'][] = ['label' => $breadcrumbs_title, 'url' => ['/blog/category/'.$breadcrumbs_url]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog-articles-view">
    <div class="blog">
        <article class="blog-article" style="">
            <?php if($model->image){ ?>
            <div class="image" style="background-image: url('<?= $model->image ?>')"></div>
            <?php } ?>
            <div class="article-body">
                <span class="article-info">
                    <span><i class="fas fa-user"></i> <a href="<?= Url::to(['/profile/'.$model->author->id]) ?>"><?= $model->author->username ?></a></span>
                    <span><i class="fas fa-server"></i>
                        <?php if($model->category->title){ ?>
                            <a href="<?= Url::to(['/blog/category/'.$model->category->alias]) ?>"><?= $model->category->title ?></a>
                        <?php }else{ ?>
                            <a href="<?= Url::to(['/blog/category/uncategorized']) ?>">Uncategorized</a>
                        <?php } ?>
                    </span>
                    <span><i class="far fa-calendar-alt"></i> <?= date('d-m-Y | H:i', $model->created_at) ?></span>
                    <?php if($model->articletag){ ?>
                        <span>
                            <i class="fas fa-tags"></i>
                            <?php foreach ($model->articletag as $item) { ?>
                                <a href="<?= Url::to(['/blog/tag/'.$item->tag->alias]) ?>"><?= $item->tag->title ?></a>
                            <?php } ?>
                        </span>
                    <?php } ?>
                    <span class="badge badge-secondary"><i class="far fa-comment-dots"></i> <?= $model->count_comments ?></span>
                    <span class="badge badge-secondary"><i class="fas fa-eye"></i> <?= $model->count_show ?></span>
                    <span class="badge badge-secondary"><i class="fas fa-eye"></i> <?= $model->count_show_all ?></span>
                </span>

                <div class="rating">
                    <div class="rating-box">
                        <div class="background-box"> </div>
                        <div id="slider-box" class="slider-box" style="width: <?= $model->mark*10 ?>%"> </div>
                        <div id="slider-select-box" class="slider-select-box"> </div>
                        <div class="image-box">
                            <?php for($i = 1; $i <= 10; $i++){ ?><div id="star-<?= $i ?>" <?php if(!$isMark && !Yii::$app->user->isGuest){ ?>onclick="setMark(<?= $i ?>)" onmouseout="document.getElementById('slider-select-box').style.width = '0%'" onmousemove="document.getElementById('slider-select-box').style.width = '<?= $i*10 ?>%'"<?php } ?> class="image" style="background-image: url('<?= '/image/design/star.png' ?>')"></div><?php } ?>
                        </div>
                    </div>
                </div>

                <h2 class="article-title"><?= $model->title ?></h2>
                <p class="article-text"><?= $model->text ?></p>
            </div>
        </article>
    </div>

    <?= $this->render('_form_comments', [
        'id_articles' => $model->id,
    ]) ?>

    <div id="form-comments-answer-hidden" class="hidden">
        <?= $this->render('_form_comments_answer', [
            'id_articles' => $model->id,
        ]) ?>
    </div>

    <?= $this->render('comment_ajax', [
        'comments' => $model->comments,
        'id_article' => $model->id,
    ]) ?>

</div>

<?php if(!$isMark && !Yii::$app->user->isGuest){ ?>
<script>
    var id_article = <?= $model->id ?>;
    var cop = document.getElementById("blog-comments-answer-form");

    function showFormComment(comment){
        document.getElementById("article-comments-answer").id_comment.value=comment;
        document.getElementById("comments-answer-text").value  = '';
        document.getElementById("answer-"+comment).appendChild(cop);
    }
</script>
<?php } ?>
