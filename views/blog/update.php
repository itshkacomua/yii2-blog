<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model itshkacomua\blog\models\Blog */

$this->title = Yii::t('app', 'Update Blog: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="blog-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
