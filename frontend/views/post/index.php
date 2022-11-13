<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = Yii::t('app', $this->params['type']->label);
$this->title = $title;
$this->params['metatag']['url'] = Url::to(['post/index', 'type' => $type], true);
$this->params['metatag']['type'] = 'article';
$this->params['metatag']['description'] = 'Các bài viết về '.$title;
$catChilren = [];
foreach($this->params['type']->category as $categoryType) {
    $catChilren[] = [
        'type' => $categoryType,
        'items' => common\models\Category::findAll(['parent_id' => null, 'type' => $categoryType]),
    ];
}
$this->params['breadcrumbs'][] = ['label' => $title, 'template' => "<li class=\"breadcrumb-item d-none\">{link}</li>\n"];
?>

<?= $this->render('/masthead', [
    // 'bgcolor' => '#17a2b8',
    'heading' => Html::encode($title),
    // 'description' => $description,
]) ?>
<div class="posts container py-5">
    <?php if($catChilren) {
        foreach($catChilren as $cats) { ?>
        <ul class="<?= Html::encode($cats['type']) ?> list-inline mb-1">
            <?php foreach($cats['items'] as $idx => $item) { ?>
                <li class="list-inline-item m-1"><a href="<?= $item->url ?>" class="btn btn-sm btn-info"><?= $item->title ?></a></li>
            <?php } ?>
        </ul>
    <?php
        }
    } //if($category->parents) ?>

    <div class="action-toolbar row">
        <div class="actions col-md-8">
        </div>
        <div class="col-md-4 search-form">
            <?= $this->render('block/_search', ['model' => $searchModel, 'action' => Url::current()]) ?>
        </div>
    </div>


    <?= yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'post-container',
        ],
        'layout' => "\n<div class=\"row row-list post-list\">\n{items}\n</div>\n<div class=\"text-center\">{pager}</div>",
        'itemView' => 'block/_item',
        'itemOptions' => [ 'class' => 'col-lg-4 col-md-4 col-sm-6 col-xs-12' ],
        //'pager' => ['options' => ['class' => 'text-center']]
    ]);
    ?>
</div>
