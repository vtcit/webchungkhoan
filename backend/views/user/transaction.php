<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
$this->title = 'Giao dịch của thành viên "'.$model->username.'"';

?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'transactionForm']]) ?>
    <p><?= Yii::t('app', 'Thêm gói dịch vụ cho thành viên này'); ?></p>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($modelPlanUser, 'plan_id')->dropDownList($availablePlans, ['prompt' => ' -- Chọn gói dịch vụ -- '])->label(false) ?>
            <?= $form->field($modelPlanUser, 'user_id')->hiddenInput(['value' => $model->id])->label(false); ?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('<i class="fas fa-plus"></i> '.Yii::t('app', 'Thêm'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="form-group">
    </div>

<?php ActiveForm::end() ?>

<hr>
<h3><?= Yii::t('app', 'Lịch sử giao dịch'); ?></h3>

<table class="table border">
    <thead class="bg-info text-light">
        <tr class="text-center">
            <th><?= Yii::t('app', 'Gói dịch vụ') ?></th>
            <th><?= Yii::t('app', 'Thời gian GD') ?></th>
            <th><?= Yii::t('app', 'Thời gian sử dụng') ?></th>
            <th><?= Yii::t('app', 'Kích hoạt') ?></th>
            <th><?= Yii::t('app', 'Thời gian') ?></th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($model->planUsers as $k => $planUser) {
            $plan = $planUser->plan; ?>
        <tr class="<?php if($planUser->end_at && $planUser->end_at < time()) echo 'bg-light text-secondary'; ?>">
            <td><?= $plan->name ?></td>
            <td class="text-center"><?= date('H:i d/m/Y', $planUser->created_at) ?></td>
            <td class="text-center"><?= $plan->during_time ?> ngày</td>
            <td class="text-center"><?= ($planUser->status==1)? '<i class="fas fa-check text-success"></i>' : '<span class="text-danger">'.Yii::t('app', 'Chưa kích hoạt').'</span>'; ?></td>
            
            <td class="text-center">
                <?php
                if($planUser->start_at) { ?>
                    <span class="badge badge-info"><?= date('d/m/Y H:i', $planUser->start_at) ?></span>
                    đến
                    <span class="badge badge-info"><?= date('d/m/Y H:i', $planUser->end_at) ?></span>
                <?php
                } else {
                    echo '-';
                } ?>
            </td>
            <td class="text-center">
                <?php if($planUser->status != 1) {
                echo Html::beginForm(['plan-user/activate', 'id' => $planUser->id, 'user_id' => $planUser->user_id], 'post', ['class' => 'form-inline']);
                // echo Html::hiddenInput('plan_id', $planUser->plan_id);
                // echo Html::hiddenInput('user_id', $planUser->user_id);
                echo Html::submitButton(
                    Yii::t('app', 'Kích hoạt ngay'),
                    ['class' => 'btn btn-sm btn-danger']
                );
                echo Html::endForm();
                } elseif($planUser->end_at && $planUser->end_at < time()) {
                    echo Yii::t('app', 'Đã hết hạn');
                    echo Html::beginForm(['plan-user/activate', 'id' => $planUser->id, 'user_id' => $planUser->user_id], 'post', ['class' => 'form-inline text-center d-block']);
                    echo Html::submitButton(
                        Yii::t('app', 'Gia hạn & kích hoạt'),
                        ['class' => 'btn btn-sm btn-warning']
                    );
                    echo Html::endForm();
                } ?>
            </td>
        </tr>
        <?php
        } ?>
    </tbody>
</table>