<?php

use common\models\RecommendationDaily;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\RecommendationDailySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Khuyến nghị thị trường');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recommendation-daily-index">


    <div class="btns mb-3">
        <?= Html::a(Yii::t('app', '<i class="fa fa-file-import"></i> Nhập từ file CSV'), ['import'], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_date',
                // 'format' => ['datetime', 'php:m-d-Y H:i:s'],
                'format' => 'raw',
                'value' => function($model){
                    return Html::a(Yii::$app->formatter->asDate($model->created_at, 'php:m/d/Y H:i:s'), ['view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'ma_ck',
                'label' => 'Danh sách Mã CK',
                'format' => 'raw',
                'value' => function($model){
                    if($data_stock = $model->data_stock) {
                        $data_stock = json_decode($data_stock);
                        return Html::a(implode(', ', array_keys((array) $data_stock)), ['view', 'id' => $model->id]);
                    }
                },
            ],
            'desc:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, RecommendationDaily $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{delete}',
            ],
        ],
    ]); ?>


</div>
