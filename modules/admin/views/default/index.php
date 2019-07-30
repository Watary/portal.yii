<?php use yii\helpers\Url;
$this->title = Yii::t('admin', 'Admin panel')?>
<div class="admin-default-index">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('admin', 'General statistic') ?></h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" style="">
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3> <?= $countUsers ?> </h3>
                                <p><?= Yii::t('admin', 'Users') ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="<?= Url::to('admin/user') ?>" class="small-box-footer"><?= Yii::t('admin', 'More info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3> <?= $countLanguages ?> </h3>
                                <p><?= Yii::t('admin', 'Languages') ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-language"></i>
                            </div>
                            <a href="<?= Url::to('admin/language') ?>" class="small-box-footer"><?= Yii::t('admin', 'More info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('admin', 'Blog') ?></h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body" style="">
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3> <?= $countBlogArticles ?> </h3>
                                <p><?= Yii::t('admin', 'Articles') ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-copy"></i>
                            </div>
                            <a href="<?= Url::to('blog/articles') ?>" class="small-box-footer"><?= Yii::t('admin', 'More info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3> <?= $countBlogCategories ?> </h3>
                                <p><?= Yii::t('admin', 'Categories') ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-hashtag"></i>
                            </div>
                            <a href="<?= Url::to('blog/categories') ?>" class="small-box-footer"><?= Yii::t('admin', 'More info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3> <?= $countBlogTags ?> </h3>
                                <p><?= Yii::t('admin', 'Tags') ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-tags"></i>
                            </div>
                            <a href="<?= Url::to('blog/tags') ?>" class="small-box-footer"><?= Yii::t('admin', 'More info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3> <?= $countBlogComments ?> </h3>
                                <p><?= Yii::t('admin', 'Comments') ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-tags"></i>
                            </div>
                            <a href="<?= Url::to('blog/comments') ?>" class="small-box-footer"><?= Yii::t('admin', 'More info') ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
