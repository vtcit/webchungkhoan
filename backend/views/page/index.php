<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $this->params['type']->label);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Create new'), ['create', 'type' => $this->params['type']->name], ['class' => 'btn btn-success', 'data-pjax' => 0]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->title, ['page/update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
                },
            ],
            [
                'attribute' => 'parent_id',
                'label' => Yii::t('app', 'Parent'),
                'value' => 'parent.title',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'contentOptions' => ['style' => 'width: 6%'],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
