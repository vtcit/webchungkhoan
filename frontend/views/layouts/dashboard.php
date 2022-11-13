<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<?php 

use yii\bootstrap4\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="container container-main py-5">
    <div class="row">
        <div class="col-md-3">
            <?= $this->render('_nav_dashboard') ?>
        </div>
        <div class="col-md-9">
          <h1><?= Html::encode($this->title) ?></h1>
          <?= $content ?>
        </div>
    </div>
</div>


<?php $this->endContent(); ?>