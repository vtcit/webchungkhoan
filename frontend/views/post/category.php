<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$description = $category->description;
$this->title = $category->title;
foreach($category->postTypes as $postType) {
	// $this->title .= ' - '.$postType->label;
    if('post' != $postType->name)
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $postType->label), 'url' => ['post/index', 'type' => $postType->name]];
}
$catChildren = [[
    'type' => $type,
    'items' => $category->categories,
]];
if($catParent = $category->parent)
{
    if($catParent->parent)
    {
        $this->params['breadcrumbs'][] = ['label' => $catParent->parent->title, 'url' => $catParent->parent->url];
    }
    $this->params['breadcrumbs'][] = ['label' => $catParent->title, 'url' => $catParent->url];
}

$this->params['breadcrumbs'][] = ['label' => $category->title, 'template' => "<li class=\"breadcrumb-item d-none\">{link}</li>\n"];
?>

<?= $this->render('/masthead', [
    // 'bgcolor' => '#17a2b8',
    'heading' => Html::encode($category->title),
    'description' => $description,
]) ?>
<div class="posts container py-5">
    <?= frontend\widgets\Alert::widget() ?>
    <?php if($catChildren) {
        foreach($catChildren as $cats) { ?>
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
            <?= yii\widgets\LinkSorter::widget([
                'options' => [
                    'class' => 'list-inline',
                ],
                'sort' => $dataProvider->sort,
                'attributes' => [
                    'created_at',
                ],
                // 'sort' => new yii\data\Sort([
                    // 'attributes' => [
                        // 'created_at' => [
                            // 'asc' => ['created_at' => SORT_ASC],
                            // 'desc' => ['created_at' => SORT_DESC],
                            // 'default' => SORT_DESC,
                            // 'label' => 'created_at',
                        // ],
                    // ]
                // ]),
            ]);
            ?>
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
