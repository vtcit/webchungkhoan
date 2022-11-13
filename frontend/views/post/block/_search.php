<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'action' => $action,
        'method' => 'get',
        'options' => [
            'class' => 'media-search',
            'data' => ['pjax' => 1],
        ],
    ]); ?>
    <div class="input-group">
        <?= Html::activeTextInput($model, 'title', ['placeholder' => Yii::t('app', 'Keywords...'), 'class' => 'form-control', 'data' => ['live-search' => 'true']]) ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fas fa-search"></i> <span class="sr-only">'.Yii::t('app', 'Search').'</span>', ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>