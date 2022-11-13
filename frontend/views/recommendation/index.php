<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
$this->title = Yii::t('app','Khuyến nghị thị trường');
?>
<?php

if($model) {
    $fields = [
        'stock_code' => Yii::t('app', 'Mã CK'),
        'tin_hieu' => Yii::t('app', 'Tín hiệu'),
        'created_at' => Yii::t('app', 'Thời gian'),
        'gia_khuyen_nghi' => Yii::t('app', 'Giá khuyến nghị'),
        'gia_hien_tai' => Yii::t('app', 'Giá hiện tại'),
        'klgd' => Yii::t('app', 'Khối lượng GD'),
        'ty_trong' => Yii::t('app', 'Tỷ trọng'),
    ];
    $data_stock = json_decode($model->data_stock);
?>

<table class="table border">
    <thead class="bg-info text-light">
        <tr>
            <?php foreach($fields as $name => $label) { ?> 
            <th><?= $label ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php if($data_stock) {
            foreach($data_stock as $k => $r) { ?>
        <tr class="<?php if($r->tin_hieu == 'SELL') echo ' bg-light' ?>" <?php if($r->note) echo 'data-toggle="tooltip" title="'.Html::encode($r->note).'"' ?>>
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
<?php
} else { 
    echo 'Hôm nay chưa có khuyến nghị nào!';
} ?>