<?php

use yii\helpers\Html;

    $this->registerJs(
    "
    jQuery(function ($) {
        $('.input-daterange').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            calendarWeeks : false,
            clearBtn: true,
            disableTouchKeyboard: true,
            endDate: 'now'
        });
    });
    ",
    \yii\web\View::POS_END,
    'my-button-handler'
);

$this->title = Yii::t('app', 'Lịch sử Khuyến nghị thị trường: '.$code);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Khuyến nghị thị trường'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<form method="get" action="">
    <div class="row">
        <div class="col-sm-3">
        
            <?= Html::dropDownList('code', $code, $stock_codes, ['class' => 'form-control', 'prompt' => '-- Chọn mã CK --']); ?>
        </div>
        <div class="col-sm-7">
            <div class="input-daterange row">
                <div class="input-group mb-3 col-md-6">
                    <input type="text" name="date_start" id="date_start" value="<?= $date_start ?>" class="form-control" placeholder="Ngày bắt đầu" aria-describedby="button-addon1">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon1"><label class="form-control-placeholder mb-0" for="date_start"><i class="fa fa-calendar"></i></label></button>
                    </div>
                </div>
                <div class="input-group mb-3 col-md-6">
                    <input type="text" name="date_end" id="date_end" value="<?= $date_end ?>" class="form-control" placeholder="Ngày kết thúc" aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2"><label class="form-control-placeholder mb-0" for="date_end"><i class="fa fa-calendar"></i></label></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </div>
</form>
<hr>
<table class="table border">
    <thead class="bg-info text-light">
        <tr>
            <?php foreach($fields as $name => $label) { ?> 
            <th><?= $label ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php if($models) {
            foreach($models as $k => $r) { ?>
        <tr class="<?php if($r->tin_hieu == 'SELL') echo ' bg-light' ?>">
            <?php foreach($fields as $name => $label) { ?>
                <td>

                    <?php if(isset($r->{$name})) {
                        $value = $r->{$name};
                        if('created_at' == $name)
                            $value = date('H:i d/m/Y', $value);
                        if('klgd' == $name)
                            $value = (float)($value);
                        if(in_array($name, ['gia_khuyen_nghi', 'gia_hien_tai']))
                            $value = number_format(intval($value), 2, '.', ',');
                        
                        echo $value;
                    } ?>
                </td>
            <?php } ?>
        </tr>
        <?php
            }
        } ?>
    </tbody>
</table>