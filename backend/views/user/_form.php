<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username', )->textInput(['disabled' => true]) ?>
    <?= $form->field($model, 'display_name')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?php
        echo $form->field($model, 'role')->dropDownList(User::getAvailableRolesList(),  ['prompt' => Yii::t('app', '-- Không có vi trò')]);
    ?>
    <?= $form->field($model, 'status')->radioList($model::getStatuses()) ?>

    <hr>
    <h3>Change Password (optional)</h3>
    <p class="hint"><?= Yii::t('app', 'Điền mật khẩu mới nếu bạn muốn thay đổi mật khẩu.') ?></p>
    <?= $form->field($model, 'new_password')->passwordInput() ?>
    <?= $form->field($model,'repeat_new_password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
