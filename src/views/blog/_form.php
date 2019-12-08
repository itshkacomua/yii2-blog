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
    <?= $form->field($model, 'title', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file', ['options' => ['class' => 'col-xs-6']])->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' =>  'Select Photo'
        ],
    ]);?>

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
