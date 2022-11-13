<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\assets\FontAwesomeAsset;
use frontend\assets\AppAsset;
use frontend\helpers\Metatag;


if(!$this->title) {
    $this->title = Yii::$app->name;
}

if(isset($this->params['metatag'])) {
    $metatag = ArrayHelper::merge($this->params['metatag'], [
        'title' => $this->title,
        // 'publisher' => 'fb',
    ]);

    (new Metatag())->register($this, $metatag);
}

FontAwesomeAsset::register($this, ['options' => ['js' => [], 'cdn' => true]]);
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="resource-type" content="document" />
    <meta name="robots" content="all, index, follow" />
    <meta name="googlebot" content="all, index, follow" />
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link href="//fonts.googleapis.com/css?family=Open+Sans%3A300%2C400%2C400i%2C600%2C700%7CRoboto%3A400%2C300%2C400i%2C500%2C700" rel="stylesheet">
    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="base-layout <?= Html::encode('r-'.$this->context->id.'_'.(($this->context->action->id != 'index')? $this->context->action->id : 'home')) ?>">
<?php $this->beginBody() ?>

<div class="wrap">
    <main class="main">
        <?= $content ?>
    </main>

    <!--Footer start-->
    <footer>
    <!--Start footer copyright-->
    <div class="bg-info text-light py-1 border-top">
        <div class="container">
        <p class="d-block mb-2 mt-2 text-center">©<?= date('Y') ?> <?= Yii::$app->name ?></p>
        </div>
    </div>
    <!--End footer copyright-->
    </footer>
    <!-- End Footer -->
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
