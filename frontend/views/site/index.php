<?php
use common\models\Page;
// use common\models\Post;
// use common\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
use common\models\Post;
use common\models\Category;

$posts = Post::find()->limit(6)->all();
$description = [
  'Chuyên gia phân tích chứng khoán',
  'Giúp nhà đầu tư bận rộn vừa có thể kiếm tiền từ kênh đầu tư chứng khoán.',
];
$this->title = 'Cty TNHH Company';
$this->params['metatag']['url'] = Url::to(['/'], true);
$this->params['metatag']['type'] = 'article';
$this->params['metatag']['description'] = implode(' - ', $description);
?>

  <?= $this->render('_hero', ['description' => $description]) ?>
  
  <main id="main">
    
    <?= $this->render('_about') ?>
    <?= $this->render('_khuyen-nghi') /** _why */ ?>
    <?php /** $this->render('_skill')  _skill */ ?>
    <?= $this->render('_khuyen-nghi-history') ?>
    <?= $this->render('_service') ?>
    <?= $this->render('_call-to-action') ?>
    <?= $this->render('_team') ?>
    <?= $this->render('_news') ?>
    <?php /** $this->render('_portfolio') */ ?>
    <?php /** $this->render('_frequently') */ ?>
    <?php /** $this->render('_client') */ ?>
    <?= $this->render('_contact') ?>

  </main>
