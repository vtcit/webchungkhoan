<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100" style="min-height: 75rem; padding-top: 4.5rem;">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid']
    ]);
    $menuItems = [
        ['label' => Yii::t('app', 'Chứng khoán'), 'url' => '#',
            'items' => [
                ['label' => 'Danh sách khuyến nghị', 'url' => ['recommendation-daily/index']],
                ['label' => 'Import CSV', 'url' => ['recommendation-daily/import']],
                ['label' => 'Lịch sử khuyến nghị', 'url' => ['recommendation-daily/history']],
                ['label' => 'Q.Lý Mã Chứng khoán', 'url' => ['stock/index']],
            ]
        ],
        ['label' => 'Gói dịch vụ', 'url' => ['plan/index']],
        ['label' => Yii::t('app', 'Media'), 'url' => ['media/index']],
        ['label' => Yii::t('app', 'User'), 'url' => ['user/index'],
            'items' => [
                ['label' => Yii::t('app', 'User Manager'), 'url' => ['user/index']],
                ['label' => Yii::t('app', 'Add a User'), 'url' => ['user/create']],
            ],
        ],
        ['label' => Yii::t('app', 'Page'), 'url' => ['page/index']],
    ];
    if(isset(Yii::$app->params['page_type']))
    {
        $page_type = Yii::$app->params['page_type'];
        foreach($page_type as $k => $item)
        {
            $menuItems[] = ['label' => $item['label'], 'url' => ['page/index', 'type' => $item['name']]];
        }
    }
    $menuItems[] = ['label' => Yii::t('app', 'Post'), 'url' => ['post/index'], 'items' => [
        ['label' => Yii::t('app', 'Post'), 'url' => ['post/index']],
        ['label' => Yii::t('app', 'Category'), 'url' => ['category/index']],
    ]];
    $category_type = isset(Yii::$app->params['category_type'])? Yii::$app->params['category_type'] : null;
    if(isset(Yii::$app->params['post_type']))
    {
        $post_type = Yii::$app->params['post_type'];
        foreach($post_type as $k => $item)
        {
            $mItem = ['label' => Yii::t('app', $item['label']), 'url' => ['post/index', 'type' => $item['name']]];
            if(isset($item['category']) && $category_type)
            {
                $mItemChildren = [];
                foreach ($item['category'] as $k => $cat_type) {
                    if(isset($category_type[$cat_type]))
                    {
                        $cat = $category_type[$cat_type];
                        $mItemChildren[] = ['label' => Yii::t('app', $cat['label']), 'url' => ['category/index', 'type' => $cat['name']]];
                    }
                }
                if($mItemChildren)
                {
                    array_unshift($mItemChildren, $mItem);
                    $mItem['items'] = $mItemChildren;
                }
            }
            $menuItems[] = $mItem;
        }
    }
    $menuItems[] = ['label' => Yii::t('app', 'Frontpage'), 'url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl('/'), 'linkOptions' => ['target' => '_blank', 'class'=> 'btn btn-sm font-underline']];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex ml-auto'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container-fluid">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
