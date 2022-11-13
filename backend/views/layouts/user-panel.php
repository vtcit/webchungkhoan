<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<?php 

use yii\bootstrap4\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="row">
    <div class="col-sm-3 col-md-2 col-lg-2">
        <div class="card card-primary mb-4">
            <div class="card-header text-light bg-primary font-weight-bold text-uppercase d-none"><?= Yii::t('app', 'User Dashboard') ?></div>
            <?php
            echo Nav::widget([
                'encodeLabels' => false,
                'items' => [
                    [ 'label' => 'User Details', 'url' => ['user/view/'.Yii::$app->request->get('id')], ],
                    [ 'label' => 'Edit User Details', 'url' => ['user/update/'.Yii::$app->request->get('id')], 'options' => ['class' => 'border-top']],
                    [ 'label' => 'Transaction', 'url' => ['user/transaction/'.Yii::$app->request->get('id')], 'options' => ['class' => 'border-top']],
                ],
                'options' => ['class' =>'flex-column nav-pills', 'role' => 'tablist', 'aria-orientation' => 'vertical'],
            ]);
            ?>
        </div>
    </div>
    <div class="col-sm-9 col-md-10 col-lg-10">
        <h1 class="mb-4 h2 border-bottom pb-3"><?= $this->title ?></h1>
        <?= $content ?>
    </div>
</div>

<?php $this->endContent(); ?>