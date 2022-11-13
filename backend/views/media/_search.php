<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MediaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    // 'action' => ['index', 'type' => 'grid'],
    'method' => 'get',
    'options' => [
        'class' => 'form-inline media-search',
        'data' => ['pjax' => 1],
    ],
]); ?>

<?= $form->field($model, 'mime_type')->DropDownList(['' => 'All MIME type', 'image/jpeg' => '.jpg', 'image/png' => '.png'], [ 'class' => 'form-control', 'data' => ['live-search' => 'true'] ])->label(false) ?>

<?= $form->field($model, 'title')->textInput(['placeholder' => Yii::t('app', 'Keywords...'), 'data' => ['live-search' => 'true']])->label(false) ?>

<?php // echo $form->field($model, 'description') ?>

<div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> '.Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

