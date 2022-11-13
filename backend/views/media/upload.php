<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = Yii::t('app', 'Upload');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'uploadForm', 'style' => 'padding: 20px 30px; border: 4px dashed #b4b9be; margin-bottom: 20px;']]) ?>

    <?= $form->field($model, 'fileUpload')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="glyphicon glyphicon-upload"></i> '.Yii::t('app', 'Upload'), ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end() ?>