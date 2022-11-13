<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app', 'Import CSV');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Khuyến nghị'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'importForm']]) ?>

    <?= $form->field($model, 'fileCsv')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-upload"></i> '.Yii::t('app', 'Upload & Import'), ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end() ?>