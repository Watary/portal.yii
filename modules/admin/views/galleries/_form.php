<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->registerCss('.select2-selection__rendered{line-height: 1.9}');
$this->registerCssFile('/css/bootstrap-colorpicker.css');
$this->registerJsFile('https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/js/select2.full.min.js', ['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/colorpicker.js', ['depends' => [yii\web\JqueryAsset::className()]]);

$gallery_setting = json_decode($model->setting, true);
if($gallery_setting['thumbnailHeight'] == 'auto'){
    $gallery_setting['gallery_layout'] = 'cascading';
} elseif ($gallery_setting['thumbnailWidth'] == 'auto'){
    $gallery_setting['gallery_layout'] = 'justified';
} else {
    $gallery_setting['gallery_layout'] = 'grid';
}

/* @var $this yii\web\View */
/* @var $model app\modules\galleries\models\GalleryGalleries */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="gallery-galleries-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'alias', ['template' => "{label}\n{hint}\n<div class='input-group'>{input}<span class='input-group-btn'><button id='generate-alias' type='button' class='btn btn-default' data-dismiss='modal'>".Yii::t('app', 'Generate alias')."</button></span></div>\n{error}"])->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_thumbnail" data-toggle="tab" aria-expanded="true">Thumbnail</a></li>
            <li class=""><a href="#tab_thumbnail_label" data-toggle="tab" aria-expanded="false">Thumbnail label</a></li>
            <li class=""><a href="#tab_colors" data-toggle="tab" aria-expanded="false">Colors</a></li>
            <li class=""><a href="#tab_navigation_filters" data-toggle="tab" aria-expanded="false">Navigation / Filters</a></li>
            <li class=""><a href="#tab_gallery" data-toggle="tab" aria-expanded="false">Gallery</a></li>
            <li class=""><a href="#tab_hover_effects" data-toggle="tab" aria-expanded="false">Hover effects</a></li>

            <li class="pull-right"><a href="#tab_result" data-toggle="tab" aria-expanded="false">Result</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_thumbnail">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Gallery layout</label>
                        <?php
                        $data = [
                            "grid" => "Grid",
                            "justified" => "Justified",
                            "cascading" => "Cascading",
                        ];
                        echo Select2::widget([
                            'name' => 'gallery_layout',
                            'value' => $gallery_setting['gallery_layout'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Display transition</label>
                        <?php
                        $data = [
                            "fadeIn" => "FadeIn",
                            "slideUp" => "SlideUp",
                            "slideDown" => "SlideDown",
                            "scaleUp" => "ScaleUp",
                            "scaleDown" => "ScaleDown",
                            "flipDown" => "FlipDown",
                            "flipUp" => "FlipUp",
                            "slideDown2" => "SlideDown2",
                            "slideUp2" => "SlideUp2",
                            "slideRight" => "SlideRight",
                            "slideLeft" => "SlideLeft",
                            "randomScale" => "RandomScale",
                        ];
                        echo Select2::widget([
                            'name' => 'thumbnailDisplayTransition',
                            'value' => $gallery_setting['thumbnailDisplayTransition'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>Thumbnail height</label>
                        <input name="thumbnailHeight" class="form-control" type="text" placeholder="200" value="<?= $gallery_setting['thumbnailHeight'] ? $gallery_setting['thumbnailHeight'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>Thumbnail width</label>
                        <input name="thumbnailWidth" class="form-control" type="text" placeholder="300" value="<?= $gallery_setting['thumbnailWidth'] ? $gallery_setting['thumbnailWidth'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>Border height</label>
                        <input name="thumbnailBorderHorizontal" class="form-control" type="text" placeholder="2 " value="<?= $gallery_setting['thumbnailBorderHorizontal'] ? $gallery_setting['thumbnailBorderHorizontal'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>Border width</label>
                        <input name="thumbnailBorderVertical" class="form-control" type="text" placeholder="2   " value="<?= $gallery_setting['thumbnailBorderVertical'] ? $gallery_setting['thumbnailBorderVertical'] : '' ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Display transition duration (ms)</label>
                        <input class="form-control" type="text" placeholder="240" name="thumbnailDisplayTransitionDuration" value="<?= $gallery_setting['thumbnailDisplayTransitionDuration'] ? $gallery_setting['thumbnailDisplayTransitionDuration'] : '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label>Transition interval duration (ms)</label>
                        <input class="form-control" type="text" placeholder="15" name="thumbnailDisplayInterval" value="<?= $gallery_setting['thumbnailDisplayInterval'] ? $gallery_setting['thumbnailDisplayInterval'] : '' ?>">
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_thumbnail_label">
                <label for="thumbnailLabel[display]">Display label</label>
                <div class="checkbox-slider">
                    <input type="checkbox" class="checkbox" name="thumbnailLabel[display]" id="thumbnailLabel[display]" <?= $gallery_setting['thumbnailLabel']['display'] == 'false' ? '' : 'checked' ?>>
                    <div class="knobs"></div>
                    <div class="layer"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="thumbnailLabel[titleFontSize]">Title font size</label>
                        <input id="thumbnailLabel[titleFontSize]" name="thumbnailLabel[titleFontSize]" class="form-control" type="text" placeholder="1em" value="<?= $gallery_setting['thumbnailLabel']['titleFontSize'] ? $gallery_setting['thumbnailLabel']['titleFontSize'] : '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="thumbnailLabel[descriptionFontSize]">Description font size</label>
                        <input id="thumbnailLabel[descriptionFontSize]" name="thumbnailLabel[descriptionFontSize]" class="form-control" type="text" placeholder="0.8em" value="<?= $gallery_setting['thumbnailLabel']['descriptionFontSize'] ? $gallery_setting['thumbnailLabel']['descriptionFontSize'] : '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Label position</label>
                        <?php
                        $data = [
                            "overImageOnBottom" => "overImageOnBottom",
                            "overImageOnTop" => "overImageOnTop",
                            "overImageOnMiddle" => "overImageOnMiddle",
                            "onBottom" => "onBottom",
                        ];
                        echo Select2::widget([
                            'name' => 'thumbnailLabel[position]',
                            'value' => $gallery_setting['thumbnailLabel']['position'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Label content alignement</label>
                        <?php
                        $data = [
                            "center" => "Center",
                            "right" => "Right",
                            "left" => "Left",
                        ];
                        echo Select2::widget([
                            'name' => 'thumbnailLabel[align]',
                            'value' => $gallery_setting['thumbnailLabel']['align'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="thumbnailLabel[titleMultiLine]">Title multiline:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailLabel[titleMultiLine]" id="thumbnailLabel[titleMultiLine]" <?= $gallery_setting['thumbnailLabel']['titleMultiLine'] == 'true' ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailLabel[displayDescription]">Display description:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailLabel[displayDescription]" id="thumbnailLabel[displayDescription]" <?= $gallery_setting['thumbnailLabel']['displayDescription'] == 'false' || !isset($gallery_setting['thumbnailLabel']['displayDescription']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailLabel[descriptionMultiLine]">Description multiline:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailLabel[descriptionMultiLine]" id="thumbnailLabel[descriptionMultiLine]" <?= $gallery_setting['thumbnailLabel']['descriptionMultiLine'] == 'true' ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailLabel[hideIcons]">Hide icons:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailLabel[hideIcons]" id="thumbnailLabel[hideIcons]" <?= $gallery_setting['thumbnailLabel']['hideIcons'] == 'false' ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_colors">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Title color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][titleColor]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['titleColor'] ? $gallery_setting['galleryTheme']['thumbnail']['titleColor'] : 'rgba(238,238,238,1)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Title background color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][titleBgColor]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['titleBgColor'] ? $gallery_setting['galleryTheme']['thumbnail']['titleBgColor'] : 'rgba(0,0,0,0)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Description color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][descriptionColor]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['descriptionColor'] ? $gallery_setting['galleryTheme']['thumbnail']['descriptionColor'] : 'rgba(204,204,204,1)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Description background color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][descriptionBgColor]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['descriptionBgColor'] ? $gallery_setting['galleryTheme']['thumbnail']['descriptionBgColor'] : 'rgba(0,0,0,0)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Border color:</label>

                        <div class="input-group colorpicker-border">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][borderColor]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['borderColor'] ? $gallery_setting['galleryTheme']['thumbnail']['borderColor'] : 'rgba(0,0,0,1)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Background color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][background]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['background'] ? $gallery_setting['galleryTheme']['thumbnail']['background'] : 'rgba(68,68,68,1)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Label background color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][labelBackground]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['labelBackground'] ? $gallery_setting['galleryTheme']['thumbnail']['labelBackground'] : 'rgba(34,34,34,0)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Stack background color:</label>

                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][stackBackground]" value="<?= $gallery_setting['galleryTheme']['thumbnail']['stackBackground'] ? $gallery_setting['galleryTheme']['thumbnail']['stackBackground'] : 'rgba(170,170,170,1)' ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="slide-container col-md-12">
                        <label>Label opacity: <span id="galleryTheme[thumbnail][labelOpacity][value]"></span></label>
                    </div>
                    <div class="slide-container col-md-12">
                        <input type="range" min="0" max="100" name="galleryTheme[thumbnail][labelOpacity]" value="<?= isset($gallery_setting['galleryTheme']['thumbnail']['labelOpacity']) ? $gallery_setting['galleryTheme']['thumbnail']['labelOpacity']*100 : '100' ?>" class="slider" id="galleryTheme[thumbnail][labelOpacity]">
                    </div>
                    <style>
                        .slider {
                            -webkit-appearance: none;
                            width: 100%;
                            height: 15px;
                            border-radius: 5px;
                            background: #cdd9de;
                            outline: none;
                            opacity: 0.7;
                            -webkit-transition: .2s;
                            transition: opacity .2s;
                        }

                        .slider::-webkit-slider-thumb {
                            -webkit-appearance: none;
                            appearance: none;
                            width: 25px;
                            height: 25px;
                            border-radius: 50%;
                            background: #0391d8;
                            cursor: pointer;
                        }

                        .slider::-moz-range-thumb {
                            width: 25px;
                            height: 25px;
                            border-radius: 50%;
                            background: #0391d8;
                            cursor: pointer;
                        }
                    </style>
                    <script>
                        var slider = document.getElementById("galleryTheme[thumbnail][labelOpacity]");
                        var output = document.getElementById("galleryTheme[thumbnail][labelOpacity][value]");
                        output.innerHTML = slider.value;

                        slider.oninput = function() {
                            output.innerHTML = this.value;
                        }
                    </script>
                </div>

                <hr>

                <div class="row">
                    <?php
                        if($gallery_setting['galleryTheme']['thumbnail']['titleShadow']){
                            $shadow = explode(" ", $gallery_setting['galleryTheme']['thumbnail']['titleShadow']);
                            $shadow_x = substr($shadow[0], 0, -2);
                            $shadow_y = substr($shadow[1], 0, -2);
                            $shadow_r = substr($shadow[2], 0, -2);
                            $shadow_color = $shadow[3];
                        }else{
                            $shadow_x = '';
                            $shadow_y = '';
                            $shadow_r = '';
                            $shadow_color = '';
                        }
                    ?>
                    <div class="col-md-12">
                        <label for="galleryTheme[thumbnail][titleShadow][enable]">Title shadow:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="galleryTheme[thumbnail][titleShadow][enable]" id="galleryTheme[thumbnail][titleShadow][enable]" <?= isset($gallery_setting['galleryTheme']['thumbnail']['titleShadow']) ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="galleryTheme[thumbnail][titleShadow][x]">X axis shift (px):</label>
                        <input id="galleryTheme[thumbnail][titleShadow][x]" name="galleryTheme[thumbnail][titleShadow][x]" class="form-control" type="text" value="<?= $shadow_x ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="galleryTheme[thumbnail][titleShadow][y]">Y axis shift (px):</label>
                        <input id="galleryTheme[thumbnail][titleShadow][y]" name="galleryTheme[thumbnail][titleShadow][y]" class="form-control" type="text" value="<?= $shadow_y ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="galleryTheme[thumbnail][titleShadow][r]">Blur radius (px):</label>
                        <input id="galleryTheme[thumbnail][titleShadow][r]" name="galleryTheme[thumbnail][titleShadow][r]" class="form-control" type="text" value="<?= $shadow_r ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Color (px):</label>
                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][titleShadow][color]" value="<?= $shadow_color ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <?php
                        if($gallery_setting['galleryTheme']['thumbnail']['descriptionShadow']){
                            $shadow_description = explode(" ", $gallery_setting['galleryTheme']['thumbnail']['descriptionShadow']);
                            $shadow_description_x = substr($shadow_description[0], 0, -2);
                            $shadow_description_y = substr($shadow_description[1], 0, -2);
                            $shadow_description_r = substr($shadow_description[2], 0, -2);
                            $shadow_description_color = $shadow_description[3];
                        }else{
                            $shadow_description_x = '';
                            $shadow_description_y = '';
                            $shadow_description_r = '';
                            $shadow_description_color = '';
                        }
                    ?>
                    <div class="col-md-12">
                        <label for="galleryTheme[thumbnail][descriptionShadow][enable]">Description shadow:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="galleryTheme[thumbnail][descriptionShadow][enable]" id="galleryTheme[thumbnail][descriptionShadow][enable]" <?= isset($gallery_setting['galleryTheme']['thumbnail']['descriptionShadow']) ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="galleryTheme[thumbnail][descriptionShadow][x]">X axis shift (px):</label>
                        <input id="galleryTheme[thumbnail][descriptionShadow][x]" name="galleryTheme[thumbnail][descriptionShadow][x]" class="form-control" type="text" value="<?= $shadow_description_x ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="galleryTheme[thumbnail][descriptionShadow][y]">Y axis shift (px):</label>
                        <input id="galleryTheme[thumbnail][descriptionShadow][y]" name="galleryTheme[thumbnail][descriptionShadow][y]" class="form-control" type="text" value="<?= $shadow_description_y ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="galleryTheme[thumbnail][descriptionShadow][r]">Blur radius (px):</label>
                        <input id="galleryTheme[thumbnail][descriptionShadow][r]" name="galleryTheme[thumbnail][descriptionShadow][r]" class="form-control" type="text" value="<?= $shadow_description_r ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Color (px):</label>
                        <div class="input-group colorpicker-background">
                            <input type="text" class="form-control" name="galleryTheme[thumbnail][descriptionShadow][color]" value="<?= $shadow_description_color ?>">
                            <div class="input-group-addon">
                                <i style="background-color: rgba(0, 0, 0, 1);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_navigation_filters">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Display tag filter</label>
                        <?php
                        $data = [
                            "false" => "Off",
                            "true" => "On",
                            "title" => "Title",
                            "description" => "Description",
                        ];
                        echo Select2::widget([
                            'name' => 'galleryFilterTags',
                            'value' => $gallery_setting['galleryFilterTags'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label>Thumbnail height</label>
                        <input name="navigationFontSize" class="form-control" type="text" placeholder="1.2em" value="<?= $gallery_setting['navigationFontSize'] ? $gallery_setting['navigationFontSize'] : '' ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="displayBreadcrumb">Display breadcrumb:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="displayBreadcrumb" id="displayBreadcrumb" <?= isset($gallery_setting['displayBreadcrumb']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="breadcrumbOnlyCurrentLevel">Breadcrumb only current level:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="breadcrumbOnlyCurrentLevel" id="breadcrumbOnlyCurrentLevel" <?= isset($gallery_setting['breadcrumbOnlyCurrentLevel']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="breadcrumbAutoHideTopLevel">Breadcrumb auto hide top level:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="breadcrumbAutoHideTopLevel" id="breadcrumbAutoHideTopLevel" <?= isset($gallery_setting['breadcrumbAutoHideTopLevel']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="breadcrumbHideIcons">Breadcrumb hide icons:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="breadcrumbHideIcons" id="breadcrumbHideIcons" <?= isset($gallery_setting['breadcrumbHideIcons']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="locationHash">Location hash:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="locationHash" id="locationHash" <?= isset($gallery_setting['locationHash']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="touchAnimation">Touch animation:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="touchAnimation" id="touchAnimation" <?= isset($gallery_setting['touchAnimation']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailLevelUp">Thumbnail level up:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailLevelUp" id="thumbnailLevelUp" <?= isset($gallery_setting['thumbnailLevelUp']) == 'true' ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_gallery">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Display mode</label>
                        <?php
                        $data = [
                            "fullContent" => "Full content",
                            "moreButton" => "More button",
                            "pagination" => "Pagination",
                            "rows" => "Rows",
                        ];
                        echo Select2::widget([
                            'name' => 'galleryDisplayMode',
                            'value' => $gallery_setting['galleryDisplayMode'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Pagination mode</label>
                        <?php
                        $data = [
                            "rectangles" => "Rectangles",
                            "dots" => "Dots",
                            "numbers" => "Numbers",
                        ];
                        echo Select2::widget([
                            'name' => 'galleryPaginationMode',
                            'value' => $gallery_setting['galleryPaginationMode'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Sorting</label>
                        <?php
                        $data = [
                            "none" => "none",
                            "titleAsc" => "Title asc",
                            "titleDesc" => "Title desc",
                            "reversed" => "Reversed",
                            "random" => "Random",
                        ];
                        echo Select2::widget([
                            'name' => 'gallerySorting',
                            'value' => $gallery_setting['gallerySorting'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Display transition</label>
                        <?php
                        $data = [
                            "none" => "none",
                            "rotateX" => "Rotate X",
                            "slideUp" => "Slide up",
                        ];
                        echo Select2::widget([
                            'name' => 'galleryDisplayTransition',
                            'value' => $gallery_setting['galleryDisplayTransition'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>

                    <div class="col-md-3">
                        <label>Display more step</label>
                        <input name="galleryDisplayMoreStep" class="form-control" type="text" placeholder="2" value="<?= $gallery_setting['galleryDisplayMoreStep'] ? $gallery_setting['galleryDisplayMoreStep'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>Max rows</label>
                        <input name="galleryMaxRows" class="form-control" type="text" placeholder="2" value="<?= $gallery_setting['galleryMaxRows'] ? $gallery_setting['galleryMaxRows'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>Visible pages</label>
                        <input name="paginationVisiblePages" class="form-control" type="text" placeholder="10" value="<?= $gallery_setting['paginationVisiblePages'] ? $gallery_setting['paginationVisiblePages'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>Max items</label>
                        <input name="galleryMaxItems" class="form-control" type="text" placeholder="0" value="<?= $gallery_setting['galleryMaxItems'] ? $gallery_setting['galleryMaxItems'] : '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label>Display transition duration</label>
                        <input name="galleryDisplayTransitionDuration" class="form-control" type="text" placeholder="1000" value="<?= $gallery_setting['galleryDisplayTransitionDuration'] ? $gallery_setting['galleryDisplayTransitionDuration'] : '' ?>">
                    </div>

                    <div class="col-md-6">
                        <label>Render delay</label>
                        <input name="galleryRenderDelay" class="form-control" type="text" placeholder="60" value="<?= $gallery_setting['galleryRenderDelay'] ? $gallery_setting['galleryRenderDelay'] : '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailOpenImage">Open image:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailOpenImage" id="thumbnailOpenImage" <?= isset($gallery_setting['thumbnailOpenImage']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="paginationSwipe">Pagination swipe:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="paginationSwipe" id="paginationSwipe" <?= isset($gallery_setting['paginationSwipe']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="galleryLastRowFull">Last row full:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="galleryLastRowFull" id="galleryLastRowFull" <?= isset($gallery_setting['galleryLastRowFull']) ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="galleryResizeAnimation">Resize animation:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="galleryResizeAnimation" id="galleryResizeAnimation" <?= isset($gallery_setting['galleryResizeAnimation']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailSelectable">Selectable:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailSelectable" id="thumbnailSelectable" <?= isset($gallery_setting['thumbnailSelectable']) ? 'checked' : '' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="thumbnailDisplayOutsideScreen">Display outside screen:</label>
                        <div class="checkbox-slider">
                            <input type="checkbox" class="checkbox" name="thumbnailDisplayOutsideScreen" id="thumbnailDisplayOutsideScreen" <?= isset($gallery_setting['thumbnailDisplayOutsideScreen']) ? '' : 'checked' ?>>
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_hover_effects">
                <?php
                $i = 0;
                foreach (explode('|', $gallery_setting['thumbnailHoverEffect2']) as $item){
                    $effect = $custom_effect = $from = $to = $duration = '';
                    $list_all_effects = ["translateX", "translateY", "scale", "rotateX", "rotateY", "rotateZ", "blur", "grayscale", "sepia"];
                    $effects_item = explode('_', $item);
                    $element = $effects_item[0];
                    if(in_array($effects_item[1], $list_all_effects)) {
                        $effect = $effects_item[1];
                    }else{
                        $custom_effect = $effects_item[1];
                    }
                    $from = $effects_item[2];
                    $to = $effects_item[3];
                    $duration = $effects_item[4];
                ?>
                    <div class="row">
                        <div class="col-md-2">
                            <label>Element:</label>
                            <?php
                            $data = [
                                "remove" => "Remove",
                                "label" => "Label",
                                "image" => "Image",
                                "thumbnail" => "Thumbnail",
                                "title" => "Title",
                                "description" => "Description",
                                "tools" => "Tools",
                            ];
                            echo Select2::widget([
                                'name' => 'hover_effects['.$i.'][element]',
                                'value' => $element,
                                'data' => $data,
                            ]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <label>Effect (list):</label>
                            <?php
                            $data = [
                                "none" => " ",
                                "translateX" => "Translate X",
                                "translateY" => "Translate Y",
                                "scale" => "Scale",
                                "rotateX" => "Rotate X",
                                "rotateY" => "Rotate Y",
                                "rotateZ" => "Rotate Z",
                                "blur" => "Blur (only for image)",
                                "grayscale" => "Grayscale (only for image)",
                                "sepia" => "Sepia (only for image)",
                            ];
                            echo Select2::widget([
                                'name' => 'hover_effects['.$i.'][effect]',
                                'value' => $effect,
                                'data' => $data,
                            ]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <label>or custom effect:</label>
                            <input name="hover_effects[<?= $i ?>][custom_effect]" class="form-control" type="text" value="<?= $custom_effect ? $custom_effect : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label>From:</label>
                            <input name="hover_effects[<?= $i ?>][from]" class="form-control" type="text" value="<?= $from ? $from : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label>To:</label>
                            <input name="hover_effects[<?= $i ?>][to]" class="form-control" type="text" value="<?= $to ? $to : '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label>Duration (ms):</label>
                            <input name="hover_effects[<?= $i ?>][duration]" class="form-control" type="text" value="<?= $duration ? $duration : '' ?>">
                        </div>
                    </div>
                <?php
                    $i++;
                }

                $i_to = $i + 3;
                for( ; $i < $i_to; $i++ ) { ?>
                <div class="row">
                    <div class="col-md-2">
                        <label>Element:</label>
                        <?php
                        $data = [
                            "remove" => "Remove",
                            "label" => "Label",
                            "image" => "Image",
                            "thumbnail" => "Thumbnail",
                            "title" => "Title",
                            "description" => "Description",
                            "tools" => "Tools",
                        ];
                        echo Select2::widget([
                            'name' => 'hover_effects['.$i.'][element]',
                            'value' => $gallery_setting['hover_effects'][$i]['element'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>
                    <div class="col-md-2">
                        <label>Effect (list):</label>
                        <?php
                        $data = [
                            "none" => " ",
                            "translateX" => "Translate X",
                            "translateY" => "Translate Y",
                            "scale" => "Scale",
                            "rotateX" => "Rotate X",
                            "rotateY" => "Rotate Y",
                            "rotateZ" => "Rotate Z",
                            "blur" => "Blur (only for image)",
                            "grayscale" => "Grayscale (only for image)",
                            "sepia" => "Sepia (only for image)",
                        ];
                        echo Select2::widget([
                            'name' => 'hover_effects['.$i.'][effect]',
                            'value' => $gallery_setting['hover_effects'][$i]['effect'],
                            'data' => $data,
                        ]);
                        ?>
                    </div>
                    <div class="col-md-2">
                        <label>or custom effect:</label>
                        <input name="hover_effects[<?= $i ?>][custom_effect]" class="form-control" type="text" value="<?= $gallery_setting['hover_effects'][$i]['custom_effect'] ? $gallery_setting['hover_effects'][$i]['custom_effect'] : '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label>From:</label>
                        <input name="hover_effects[<?= $i ?>][from]" class="form-control" type="text" value="<?= $gallery_setting['hover_effects'][$i]['from'] ? $gallery_setting['hover_effects'][$i]['from'] : '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label>To:</label>
                        <input name="hover_effects[<?= $i ?>][to]" class="form-control" type="text" value="<?= $gallery_setting['hover_effects'][$i]['to'] ? $gallery_setting['hover_effects'][$i]['to'] : '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label>Duration (ms):</label>
                        <input name="hover_effects[<?= $i ?>][duration]" class="form-control" type="text" value="<?= $gallery_setting['hover_effects'][$i]['duration'] ? $gallery_setting['hover_effects'][$i]['duration'] : '' ?>">
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_result">
                <pre><?php print_r(json_decode($model->setting)) ?></pre>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-lg btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script =  <<< JS
    $('#generate-alias').on( 'click', function( event ){
        title = document.getElementById('gallerygalleries-title').value;
        $.ajax({
            url         : generate_alias,
            type        : 'POST',
            data        : {
                url:  title,
                gallery:  gallery,
            },
            success: function (data) {
                console.log(data.message);
                document.getElementById('gallerygalleries-alias').value = data.message;
            }    
        });
    });
JS;
$this->registerJsVar('generate_alias',  Url::toRoute('/admin/galleries/generate-alias', true));
$this->registerJsVar('gallery',  $model->id);
$this->registerJs($script);
?>

<?php
$script =  <<< JS
$(function () {
    $('.colorpicker-border').colorpicker();
    $('.colorpicker-background').colorpicker();
    $('.select-gallery-layout').select2();
    $('.select-display-transition').select2();
})
JS;
$this->registerJs($script);
?>
