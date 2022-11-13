<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Stocks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">

    <p>
        <?= Html::a(Yii::t('app', '<i class="fa fa-plus"></i> Create New'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', '<i class="fa fa-file-import"></i> Import'), ['import'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a(Yii::t('app', '<i class="fa fa-file-export"></i> Export'), ['export'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'exchange',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
