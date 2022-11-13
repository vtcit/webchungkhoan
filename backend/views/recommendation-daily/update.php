<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RecommendationDaily $model */

$this->title = Yii::t('app', 'Update Recommendation Daily: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recommendation Dailies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="recommendation-daily-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
