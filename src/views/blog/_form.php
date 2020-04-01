<?php

use itshkacomua\blog\models\Blog;
use itshkacomua\blog\models\Tag;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model itshkacomua\blog\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="row">
        <div class="col-xs-3">
            <?
            if (!empty($model->image)) {
                echo Html::img($model->bigImage, ['width' => 150, 'alt' => $model->title, 'class' => 'blog-image']);
                echo Html::tag('bottom', '<span class="glyphicon glyphicon-trash"></span>', [
                    'class' => 'btn btn-danger js_blog_image_delete',
                    'data' => [
                        'id' => $model->id
                    ],
                ]);
            } else {
                echo $form->field($model, 'file')->fileInput();
            }
            ?>
        </div>
        <div class="col-xs-9">
            <?= $form->field($model, 'title', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'alias', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status_id', ['options' => ['class' => 'col-xs-6']])->dropDownList(Blog::STATUS_LIST) ?>

            <?= $form->field($model, 'sort', ['options' => ['class' => 'col-xs-6']])->textInput() ?>

            <?= $form->field($model, 'tags_array', ['options' => ['class' => 'col-xs-6']])->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
                'language' => 'de',
                'options' => ['placeholder' => 'ВЫбрать tag ...', 'multiple' => true],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tags' => true,
                    'maximumInputLength' => 10
                ],
            ]);?>

            <?= $form->field($model, 'language_id', ['options' => ['class' => 'col-xs-6']])->dropDownList(Blog::GET_ID_BY_URL) ?>
        </div>
    </div>

    <?= $form->field($model, 'text')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => Url::to(['/site/save-redactor-img', 'sub' => 'blog']),
            'plugins' => [
                'fullscreen',
            ],
        ],
    ]);?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$url_ad_delete = Url::to(['/blog/blog/image-delete']);
$script_ad = <<< JS
$('.js_blog_image_delete').click(function(e) {
    var id = $(this).attr('data-id');

    if(id > 0) {
        if(confirm("Удалить изображение?")){
            $.ajax({
                type:'POST',
                cache: false,
                url: "{$url_ad_delete}",
                data: {
                    id : id
                },
                success  : function(response) {
                    if(response) {
                        $('.blog-image').hide();
                        $('.js_blog_image_delete').hide();
                        alert('Изображение удалено!');                                      
                    }
                }
            });                        
        }
    }

    return false;
});
JS;
$this->registerJs($script_ad, yii\web\View::POS_READY);
?>