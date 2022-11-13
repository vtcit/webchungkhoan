<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
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

  <?= Html::csrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&display=swap" rel="stylesheet">
  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  
  <?php $this->head() ?>
</head>

<body class="main-layout <?= Html::encode('r-'.$this->context->id.'_'.(($this->context->action->id != 'index')? $this->context->action->id : 'home')) ?>">
  <?php $this->beginBody() ?>
  <?php
    $isHome = ($this->context->id.'-'.$this->context->action->id == 'site-index');
  ?>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top <?php if(!$isHome) echo 'header-inner-pages' ?>">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="<?= Yii::$app->homeUrl ?>">WebCK</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar">
      <?php
        // NavBar::begin([
        //     'brandLabel' => $_SERVER['SERVER_NAME'],
        //     'brandUrl' => Yii::$app->homeUrl,
        //     'options' => [
        //       'id' => 'navbar',
        //         'class' => 'navbar',
        //     ],
        // ]);
        $menuItems = [
            ['label' => Yii::t('app', 'Giới thiệu'), 'url' => ['page/view', 'slug' => 'gioi-thieu', 'type' => 'page'], ['class'=>'a']],
            ['label' => Yii::t('app', 'Khuyến nghị thị trường'), 'url' => ['recommendation/index']],
            ['label' => Yii::t('app', 'Lịch sử khuyến nghị'), 'url' => ['recommendation/history']],
            ['label' => Yii::t('app', 'Tin tức'), 'url' => ['post/index', 'type' => 'post'],
                'items' => [
                    // ['label' => Yii::t('app', 'Công nghệ'), 'url' => ['post/category', 'slug' => 'cong-nghe', 'type' => 'category']],
                ],
            ],
            ['label' => Yii::t('app', 'Liên Hệ'), 'url' => ['/site/contact']],
            // ['label' => Yii::t('app', 'Get Started'), 'url' => ['/user/signup'], 'linkOptions' => ['class' => 'getstarted']],
        ];
        /* SHOPPING CART */
        // $menuItems[] = ['label' => Yii::t('app', 'Giỏ hàng'). ' <span class="badge badge-danger totalCount">0</span>', 'url' => ['cart/checkout', 't' => time()], 'linkOptions' => ['class' => 'btn btn-warning text-light btn-cart-checkout py-1 px-2 my-1 ml-2 nav-link']];
        
        if (Yii::$app->user->isGuest) {
            $menuItems[] = [
              'label' => '<i class="fa fa-user"></i>',
              'items' => [
                ['label' => Yii::t('app', 'Signup'), 'url' => ['/user/signup']],
                ['label' => Yii::t('app', 'Login'), 'url' => ['/user/login']],
              ],
            ];
        } else {
            $menuItems[] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['/dashboard/index']];
            $menuItems[] = '<li class="nav-item">'
                . Html::beginForm(['/user/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    Yii::t('app', 'Logout ({0})', [Yii::$app->user->identity->username]),
                    ['class' => 'btn btn-link logout p-2']
                )
                . Html::endForm()
                . '</li>';
        }
        echo Nav::widget([
            'encodeLabels' => false,
            'options' => false,
            'items' => $menuItems,
        ]);
        // NavBar::end();
        ?>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <?php // Pjax::begin(['id' => 'pjax-post', 'scrollTo' => true, 'timeout' => 10000]); ?>
    <?= $content ?>
  <?php // Pjax::end(); ?>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-4 col-md-6 footer-contact">
            <h3>Web CK</h3>
            <p>
              H2/1, KP6, Cư xá A42, phường Trung Dũng, thành phố Biên Hòa, tỉnh Đồng Nai.<br>
            </p>
            <p>
              <strong>Phone:</strong> 0906.794.987</p>
              <p>
              <strong>Email:</strong> tranphuongha2012@gmail.com</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Menu chính</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Trang chủ</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Khuyến nghị thị trường</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Lịch sử khuyến nghị</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Gói dịch vụ</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Tin tức</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Gói dịch vụ</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Gói Starter</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Gói Professional</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Gói Pro 1</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Gói Pro 2</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links text-center">
            
            <h4>Theo dõi mạng xã hội</h4>
            <p>Tin tức luôn được chia sẻ và cập nhật</p>
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
              <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
            <div class="footer-newsletter">
              <h4 las="pb-1">Nhận bản tin</h4>
              <form action="" method="post">
                <input type="email" name="email"><input type="submit" value="Đăng ký">
              </form> 
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; Copyright <strong><span>Web CK</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="#">CITGROUP.vn</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
