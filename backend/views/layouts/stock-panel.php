<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<?php 

use yii\bootstrap4\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="row mt-5">
    <div class="col-sm-3 col-md-2 col-lg-2">
        <div class="card card-primary mb-4">
            <div class="card-header text-light bg-primary font-weight-bold text-uppercase">Chứng khoán</div>
            <?php
            echo Nav::widget([
                'encodeLabels' => false,
                'items' => [
                        ['label' => 'Danh sách khuyến nghị', 'url' => ['recommendation-daily/index']],
                        ['label' => 'Import CSV', 'url' => ['recommendation-daily/import'], 'options' => ['class' => 'border-top']],
                        ['label' => 'Export mẫu CSV', 'url' => ['recommendation-daily/export'], 'options' => ['class' => 'border-top']],
                        ['label' => 'Lịch sử khuyến nghị', 'url' => ['recommendation-daily/history'], 'options' => ['class' => 'border-top']],
                        [ 'label' => 'Q.Lý Mã Chứng khoán', 'url' => ['stock/index'], 'options' => ['class' => 'border-top'],
                    ],
                ],
                'options' => ['class' =>'flex-column', 'role' => 'tablist', 'aria-orientation' => 'vertical'],
            ]);
            ?>
        </div>
    </div>
    <div class="col-sm-9 col-md-10 col-lg-10">
        <h1 class="mb-4 h2 border-bottom pb-3"><?= $this->title ?></h1>
        <?= $content ?>
    </div>
</div>
<style>
    .nav-link {
        color: #333;
    }
    .nav-link:hover,
    .nav-link.active {
        color: #007bff;
        background-color: #eee;
    }
</style>
<?php $this->endContent(); ?>