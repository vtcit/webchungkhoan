<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RecommendationDaily $model */

$this->title = Yii::t('app', 'Create Recommendation Daily');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Recommendation Dailies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recommendation-daily-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
