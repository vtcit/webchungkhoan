<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = Yii::t('app', 'Update {type}: {name}', [
    'name' => $model->title,
    'type' => $this->params['type']->label,
]);
$this->params['breadcrumbs'][] = ['label' => $this->params['type']->label, 'url' => ['category/index', 'type' => $this->params['type']->name]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="category-update">
    <p>
        <?= Html::a(Yii::t('app', 'Create Category'), ['create', 'type' => $this->params['type']->name], ['class' => 'btn btn-success']) ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
