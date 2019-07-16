<?php
use yii\helpers\Url;

/**
 * @var int $page
 * @var int $count_pages
 * @var string $url
 */
if(!$url) {
    $url = Yii::$app->request->absoluteUrl;

    while ($url[(strlen($url))] != "/") {
        $url = substr($url, 0, -1);
    }
}else{
    $url = Url::to(['/'.$url]).'/';
}

if($page <= 5){
    $start = 1;
}else{
    $start = $page - 2;
}

if($count_pages - $page <= 4){
    $end = $count_pages;
}else{
    $end = $page + 2;
}
?>

<nav aria-label="Page navigation example" style="text-align: center">
    <ul class="pagination blog-pagination">
        <?php if ($page != 1){ ?>
            <li class="page-item">
                <a class="page-link" href="<?= $url.($page-1) ?>">&laquo;</a>
            </li>
        <?php }else{ ?>
            <li class="page-item disabled">
                <span class="page-link">&laquo;</span>
            </li>
        <?php } ?>

        <?php if($start != 1){ ?>
            <li class="page-item"><a class="page-link" href="<?= $url.'1' ?>">1</a></li>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php } ?>

        <?php for($i = $start; $i <= $end; $i++){ ?>
            <?php if($i == $page){ ?>
                <li class="page-item active"><span class="page-link"><?= $i ?></span></li>
            <?php }else{ ?>
                <li class="page-item"><a class="page-link" href="<?= $url.$i ?>"><?= $i ?></a></li>
            <?php } ?>
        <?php } ?>

        <?php if($end != $count_pages){ ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
            <li class="page-item"><a class="page-link" href="<?= $url.$count_pages ?>"><?= $count_pages ?></a></li>
        <?php } ?>

        <?php if ($page != $count_pages){ ?>
            <li class="page-item">
                <a class="page-link" href="<?= $url.($page+1) ?>">&raquo;</a>
            </li>
        <?php }else{ ?>
            <li class="page-item disabled">
                <span class="page-link">&raquo;</span>
            </li>
        <?php } ?>
    </ul>
</nav>