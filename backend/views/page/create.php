<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = Yii::t('app', 'Create {type}', ['type' => $this->params['type']->label]);
$this->params['breadcrumbs'][] = ['label' => $this->params['type']->label, 'url' => ['index', 'type' => $this->params['type']->name]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
