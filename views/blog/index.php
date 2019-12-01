<?php

use common\modules\blog\models\Blog;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel itshkacomua\blog\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Blogs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Blog'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?$role = (Yii::$app->user->can('admin') ? '{view} {update} {chack} {delete}':'{view} {update} {chack}');?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['attribute' => 'title', 'format' => 'raw', 'headerOptions' => ['class'=>'test']],
            //'text:ntext',
            'alias:url',
            ['attribute' => 'status_id', 'filter' => function($model){return $model->getStatusList;},'value'=>function($model){
                return $model->statusName;//'statusName'
            }],
            ['attribute' => 'tags', 'value' => 'tagsAsString'],
            'sort',
            'create_time:datetime',
            'update_time:datetime',
            'smallImage:image',
            [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => $role,
                    'buttons' => [
                            'chack'=>function($url, $model, $key){
                                return HTML::a('<i class="fa fa-chack" aria-hidden="true"></i>',$url);
                            }
                    ],
                    /*'buttonOptions' => [
                        'chack' => function ($url, $model, $key) {
                            return $model->status_id === 0 ? Html::a('Chack', $url) : '';
                        },
                    ],*/
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
