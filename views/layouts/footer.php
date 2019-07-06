<?php

use yii\helpers\Url;
use app\widgets\LanguageSelect;

?>
<footer id="footer" class="footer clearfix">
    <div class="container">
        <div class="row footer-lists">
            <div class="col-sm-6 col-md-3">
                <a href="<?= Url::toRoute('/site/contact', true) ?>"><?= Yii::t('app', 'Сontact us') ?></a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a href="<?= Url::toRoute('/blog', true) ?>"><?= Yii::t('app', 'Blog') ?></a>
            </div>
            <div class="col-sm-6 col-md-3"></div>
            <div class="col-sm-6 col-md-3"></div>
        </div>

        <div class="row">
            <p class="col-sm-6">&#169; <?= Yii::t('app', 'Pedorenko Sergey') ?></p>
            <?= LanguageSelect::widget() ?>
        </div>
    </div>
</footer>