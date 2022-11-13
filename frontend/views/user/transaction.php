<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
$this->title = 'Lịch sử giao dịch';

?>

<table class="table border">
    <thead class="bg-info text-light">
        <tr class="text-center">
            <th><?= Yii::t('app', 'Gói dịch vụ') ?></th>
            <th><?= Yii::t('app', 'Thời gian GD') ?></th>
            <th><?= Yii::t('app', 'Thời gian sử dụng') ?></th>
            <th><?= Yii::t('app', 'Kích hoạt') ?></th>
            <th><?= Yii::t('app', 'Thời gian') ?></th>
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
        </tr>
        <?php
        } ?>
    </tbody>
</table>