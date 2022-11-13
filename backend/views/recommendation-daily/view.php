<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\RecommendationDaily $model */

$this->title = $model->id.': '.$model->created_date;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recommendation Dailies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="recommendation-daily-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:m-d-Y H:i:s'],
            ],
            // 'data_stock:ntext',
            [
                'attribute' => 'data_stock',
                'label' => 'Dữ liệu',
                'format' => 'raw',
                'value' => function($model){
                    if($data_stock = $model->data_stock) {
                        $data_stock = json_decode($data_stock);
                        return $this->render('_daily_item', ['data' => $data_stock]);
                    }
                },
            ],
            'desc:ntext',
        ],
    ]) ?>

</div>
