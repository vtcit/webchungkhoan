<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container container-main">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-9">
            <p>Please fill out the following fields to change password :</p>
            
            <?php $form = ActiveForm::begin([
                'id'=>'changepassword-form',
                'options'=>['class'=>'form-horizontal'],
            ]); ?>
                <?= $form->field($model, 'old_password', ['inputOptions' => [
                    'placeholder' => 'Old Password'
                ]])->passwordInput() ?>
                
                <?= $form->field($model, 'new_password', ['inputOptions' => [
                    'placeholder'=>'New Password'
                ]])->passwordInput() ?>
                
                <?= $form->field($model,'repeat_new_password', ['inputOptions'=> [
                    'placeholder'=>'Repeat New Password'
                ]])->passwordInput() ?>
                
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-11">
                        <?= Html::submitButton('Change password',[
                            'class'=>'btn btn-primary'
                        ]) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
