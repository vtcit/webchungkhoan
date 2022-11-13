<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
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
<body class="main-layout <?= Html::encode('r-'.$this->context->id.'_'.(($this->context->action->id != 'index')? $this->context->action->id : 'home')) ?>">
<?php $this->beginBody() ?>

<div class="wrap">
    <header id="main">
        <?php
        NavBar::begin([
            'brandLabel' => $_SERVER['SERVER_NAME'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-light bg-light shadow-sm',
            ],
        ]);
        $menuItems = [
            ['label' => Yii::t('app', 'Giới thiệu'), 'url' => ['page/view', 'slug' => 'gioi-thieu', 'type' => 'page']],
            ['label' => Yii::t('app', 'Tư vấn'), 'url' => ['post/category', 'slug' => 'tu-van', 'type' => 'category']],
            ['label' => Yii::t('app', 'Tin tức'), 'url' => ['#'],
                'items' => [
                    ['label' => Yii::t('app', 'Công nghệ'), 'url' => ['post/category', 'slug' => 'cong-nghe', 'type' => 'category']],
                    ['label' => Yii::t('app', 'Kinh doanh'), 'url' => ['post/category', 'slug' => 'kinh-doanh', 'type' => 'category']],
                    ['label' => Yii::t('app', 'Cuộc sống'), 'url' => ['post/category', 'slug' => 'cuoc-song', 'type' => 'category']],
                    ['label' => Yii::t('app', 'Sức khỏe'), 'url' => ['post/category', 'slug' => 'suc-khoe', 'type' => 'category']],
                    ['label' => Yii::t('app', 'Giải trí'), 'url' => ['post/category', 'slug' => 'giai-tri', 'type' => 'category']],
                ],
            ],
            ['label' => Yii::t('app', 'Liên Hệ'), 'url' => ['/site/contact']],
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
            $menuItems[] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['dashboard/index']];
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
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
    </header>
    <main class="main">
    <?php // Pjax::begin(['id' => 'pjax-post', 'scrollTo' => true, 'timeout' => 10000]); ?>
        <?= $content ?>
    <?php // Pjax::end(); ?>
    </main>

  <!--Footer start-->
  <footer>
    <!--Footer content-->
    <div id="footer" class="bg-info text-light py-5">
      <div class="container">
        <div class="row">
          <!-- left widget -->
          <div class="widget col-md-4">
            <h3 class="h5 border-bottom pb-2 h-title text-uppercase">Về chúng tôi</h3>
            <div class="widget-content">
              <h3 class="h4 mt-3 font-uppercase text-warning">Cty TNHH Company</h3>
              <p class="font-weight-light"><em>Company mong muốn đồng hành cùng cá nhân và doanh nghiệp trên con đường thành công.</em></p>
              <ul class="list-unstyled footer-info">
                  <li><address class="mb-0"><i class="fa fa-map-marker-alt"></i> Quận Ba Đình, Hà Nôi, Việt Nam</address></li>
                  <li><i class="fa fa-phone"></i> +(123) 456-7890</li>
                  <li><i class="fa fa-envelope"></i> <a href="mailto:info@company.com">info@company.com</a></li>
              </ul>
            </div>
          </div>
        <!-- center widget -->
          <div class="widget col-sm-6 col-md-4">
            <h3 class="h5 border-bottom pb-2 h-title text-uppercase">Mục lục</h3>
            <div class="row">
              <div class="col-sm-6">
                <ul class="menu">
                  <li><a href="/page/gioi-thieu/">Giới thiệu</a></li>
                  <li><a href="/category/tu-van/">Tư vấn</a></li>
                  <li><a href="/lien-he/">Liên hệ</a></li>
                </ul>
              </div>
              <div class="col-sm-6">
                <ul class="menu">
                  <li></li>
                </ul>
              </div>
            </div>
          </div>
          <!-- right widget -->
          <div class="widget col-sm-6 col-md-4">
            <h3 class="h5 border-bottom pb-2 h-title text-uppercase">Nhận bản tin</h3>
            <p>Đăng ký để nhận bản tin qua email. Bạn sẽ nhận thông báo ngay khi có bài viết mới.</p>
            <!--form-->
            <div class="mx-auto subscribe">
              <?= Html::beginForm(['mail/subscribe'], 'post', ['id' => 'subscribe', 'class' => 'form-validate']); ?>
                  <div class="input-group">
                      <?= Html::textInput('email', '', ['type' => 'email', 'required' => true, 'placeholder' => Yii::t('app', 'Nhập email của bạn'), 'class' => 'form-control', 'data' => ['live-search' => 'true']]) ?>
                      <div class="input-group-append">
                          <?= Html::submitButton('<i class="fas fa-check"></i> '.Yii::t('app', 'Đăng ký'), ['class' => 'btn btn-warning']) ?>
                      </div>
                  </div>
              <?= Html::endForm(); ?>
            </div>
            <!--end form-->

            <h3 class="h5 border-bottom mt-4 mb-3 pb-2 h-title text-uppercase">Kết nối với chúng tôi</h3>
            <div class="social mb-4">
              <!--facebook-->
              <span class="my-2 mr-3">
                <a target="_blank" href="https://facebook.com" title="Facebook"><i class="fab fa-facebook fa-2x"></i></a>
              </span>
              <!--twitter-->
              <span class="my-2 mr-3">
                <a target="_blank" href="https://twitter.com" title="Twitter"><i class="fab fa-twitter fa-2x"></i></a>
              </span>
              <!--youtube-->
              <span class="my-2 mr-3">
                <a target="_blank" href="https://youtube.com" title="Youtube"><i class="fab fa-youtube fa-2x"></i></a>
              </span>
              <!--Linkedin-->
              <span class="my-2 mr-3">
                <a target="_blank" href="https://linkedin.com" title="Linkedin"><i class="fab fa-linkedin fa-2x"></i></a>
              </span>
              <!--instagram-->
              <span class="my-2 mr-3">
                <a target="_blank" href="https://instagram.com" title="Instagram"><i class="fab fa-instagram fa-2x"></i></a>
              </span>
              <!--end instagram-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--End footer content-->
    <!--Start footer copyright-->
    <div class="bg-info text-light py-1 border-top">
      <div class="container">
        <p class="d-block mb-2 mt-2 text-center">©<?= date('Y') ?> <?= Yii::$app->name ?></p>
      </div>
    </div>
    <!--End footer copyright-->
  </footer>
  <!-- End Footer -->

</div><!-- wrap -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
