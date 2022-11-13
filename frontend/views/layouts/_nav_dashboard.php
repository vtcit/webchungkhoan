
<?php
use yii\bootstrap4\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card card-primary border-0">
    <div class="card-header text-light bg-primary font-weight-bold text-uppercase"><?= Yii::t('app', 'Chứng khoán') ?></div>
    <?php
    echo Nav::widget([
        'encodeLabels' => false,
        'items' => [
            [ 'label' => Yii::t('app', 'Khuyến nghị thị trường'), 'url' => ['recommendation/index'], ],
            [ 'label' => Yii::t('app', 'Lịch sử khuyến nghị'), 'url' => ['recommendation/history'], ],
        ],
        'options' => ['class' =>'flex-column nav-pills', 'role' => 'tablist', 'aria-orientation' => 'vertical'],
    ]);
    ?>
</div>

<div class="card card-primary border-0 mt-4">
    <div class="card-header text-light bg-primary font-weight-bold text-uppercase"><?= Yii::t('app', 'Tài khoản') ?></div>
    <?php
    echo Nav::widget([
        'encodeLabels' => false,
        'items' => [
            [ 'label' => Yii::t('app', 'Thông tin cá nhân'), 'url' => ['user/profile'], ],
            [ 'label' => Yii::t('app', 'Thông tin cá nhân'), 'url' => ['user/profile'], ],
            [ 'label' => Yii::t('app', 'Đổi mật khẩu'), 'url' => ['user/change-password'], ],
            [ 'label' => Yii::t('app', 'Lịch sử giao dịch'), 'url' => ['user/transaction'], ],
        ],
        'options' => ['class' =>'flex-column nav-pills mb-4', 'role' => 'tablist', 'aria-orientation' => 'vertical'],
    ]);
    ?>
</div>

