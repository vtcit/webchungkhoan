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
    'Chuyên cung cấp các dịch vụ và sản phẩm uy tín',
    'Tại Hà Nội, Hà Nam, Hưng Yên và các tỉnh trên toàn quốc.',
];
$this->title = 'Cty TNHH Company';
$this->params['metatag']['url'] = Url::to(['/'], true);
$this->params['metatag']['type'] = 'article';
$this->params['metatag']['description'] = implode(' - ', $description);
?>
<?= $this->render('/masthead', [
    'id' => 'welcome',
    // 'bgcolor' => '#17a2b8',
    'wavcolor' => '#f8f9fa',
    'card' => '<img src="/images/work.png" alt="image" class="mb-n3" />',
    'heading' => 'Cty TNHH Thương mai và dịch vụ <span class="text-warning">Company</span>',
    'description' => '<p>'.$description[0].'</p><p class="small font-weight-light font-italic line-height-1">'.$description[1].'</p>',
    'buttons' => [
        '<a href="#" class="btn btn-lg btn-danger shadow my-2 mr-2">Đăng ký ngay <i class="fa fa-arrow-right d-none d-md-inline ml-3"></i></a>',
        '<a href="#services" class="btn btn-lg btn-warning shadow my-2">Xem dịch vụ</a>',
    ]
]) ?>

<?php
    $pages = Page::findAll(['slug' => ['dich-vu-1', 'dich-vu-2', 'dich-vu-3']]);
    $pages = ArrayHelper::index($pages, 'slug');
?>
<section id="services" class="py-5 bg-light">
    <div class="container">
        <header class="header-section text-center" data-aos="flip-up">
            <h2><a href="" class="text-uppercase">Dịch vụ</a></h2>
            <p class="lead">Chúng tôi cung cấp các dịch vụ và sản phẩm tuyệt hảo tới các doanh nghiệp</p>
            <hr class="line pb-3 w-50 w-md-25" />
        </header>
        <div class="row">
            <div class="thiet-ke-web col-12 col-md-4 pb-5 pb-md-0" data-aos="fade-up" data-aos-delay="100">
                <?php $page = $pages['dich-vu-1']; ?>
                <div class="text-center mb-3"><i class="fa fa-5x fa-globe-asia text-info"></i></div>
                <h3 class="text-center"><a href="<?= $page->url ?>"><?= $page->title ?></a></h2>
                <div class="desctiption">
                    <p>
                        <?php
                        $page->meta = ArrayHelper::map($page->pageMetas, 'key', 'value');
                        if(isset($page->meta['price'])) {
                            echo '<span class="d-block font-italic text-center text-warning">Chỉ từ '.number_format(intval($page->meta['price'])).'đ</span>';
                        }
                        ?>
                        <?= $page->excerpt ?>
                    </p>
                </div>
                <div class="btns text-center">
                    <a href="<?= $page->url ?>" class="btn btn-sm btn-info shadow-sm"><i class="fa fa-angle-right"></i> <?= Yii::t('app', 'Read more') ?></a>
                    <a href="#" class="btn btn-sm btn-warning shadow-sm"><?= Yii::t('app', 'Xem dự án') ?></a>
                </div>
            </div>
            <div class="thiet-ke-do-hoa col-12 col-md-4 pb-5 pb-md-0" data-aos="fade-up" data-aos-delay="200">
                <?php $page = $pages['dich-vu-2']; ?>
                <div class="text-center mb-3"><i class="fa fa-5x fa-drafting-compass text-info"></i></div>
                <h3 class="text-center"><a href="<?= $page->url ?>"><?= $page->title ?></a></h2>
                <p class="desctiption"><?= $page->excerpt ?></p>
                <div class="btns text-center">
                    <a href="<?= $page->url ?>" class="btn btn-sm btn-info shadow-sm"><i class="fa fa-angle-right"></i> <?= Yii::t('app', 'Read more') ?></a>
                    <a href="#" class="btn btn-sm btn-warning shadow-sm"><?= Yii::t('app', 'Xem HSNL') ?></a>
                </div>
            </div>
            <div class="marketing-online col-12 col-md-4 pb-5 pb-md-0" data-aos="fade-up" data-aos-delay="400">
                <?php $page = $pages['dich-vu-3']; ?>
                <div class="text-center mb-3"><i class="fa fa-5x fa-chart-pie text-info"></i></div>
                <h3 class="text-center"><a href="<?= $page->url ?>"><?= $page->title ?></a></h2>
                <p class="desctiption"><?= $page->excerpt ?></p>
                <div class="btns text-center">
                    <a href="<?= $page->url ?>" class="btn btn-sm btn-info shadow-sm"><i class="fa fa-angle-right"></i> <?= Yii::t('app', 'Read more') ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="main-area" class="py-5">
    <div class="container">
        <div class="row">
                <div class="col-md-8 main-col">
                    <?= $this->render('/post/block/block-1', ['cat' => Category::findOne(['slug' => 'tu-van']), 'posts' => $posts]) ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $this->render('/post/block/block-2', ['cat' => Category::findOne(['slug' => 'cong-nghe']), 'posts' => $posts]) ?>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->render('/post/block/block-2', ['cat' => Category::findOne(['slug' => 'giai-tri']), 'posts' => $posts]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-none d-md-block right-col">
                    <?= $this->render('/post/block/social') ?>

                    <?= $this->render('/post/block/block-list', ['cat' => Category::findOne(['slug' => 'kinh-doanh']), 'posts' => $posts]) ?>

                    <?= $this->render('/post/block/block-3', ['cat' => Category::findOne(['slug' => 'cuoc-song']), 'posts' => $posts]) ?>
                </div>
        </div>
    </div>
</section>

<section class="guide py-5 bg-light">
    <div class="container main-col">
        <?= $this->render('/post/block/block-4-3', ['cat' => Category::findOne(['slug' => 'suc-khoe']), 'posts' => $posts]) ?>
    </div>
</section>
<section class="py-3 bg-warning" data-aos="fade-zoom-in">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9 col-xs-12 lead text-center text-md-right">Bạn đang cần một dịch vụ tuyệt hảo cho doanh nghiệp mình?</div>
            <div class="col-md-3 col-xs-12 text-center text-md-left mt-2 mt-md-0"><a href="#" class="btn btn-danger"><?= Yii::t('app', 'Tư vấn cho tôi') ?></a></div>
        </div>
    </div>
</section>

