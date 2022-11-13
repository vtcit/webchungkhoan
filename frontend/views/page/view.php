<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Post;
use common\models\Category;

$posts = Post::find()->limit(6)->all();

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['metatag']['url'] = Url::to($model->url, true);
$this->params['metatag']['type'] = 'article';
$this->params['metatag']['description'] = $model->excerpt;
// $model->media
$thumb = false;
if($media = $model->imageFeatured) {
    $sizes = json_decode($media->sizes);
    if(isset($sizes->large)) {
        $thumb = $sizes->large;
    }
}
if($thumb) {
    $this->params['metatag']['image'] = Url::to(['@uploaddir/'.$thumb->file], true);
    $this->params['metatag']['image_width'] = $thumb->width;
    $this->params['metatag']['image'] = $thumb->height;
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->typeObj->label), 'url' => ['index', 'type' => $model->type]];
if($model->parent) {
    $this->params['breadcrumbs'][] = ['label' => $model->parent->title, 'url' => $model->parent->url];
}
$metas = ArrayHelper::map($model->pageMetas, 'key', 'value');
?>

<article id="page-<?= $model->hashid ?>" class="hentry article-detail">
    <?= $this->render('/masthead', [
        'bgcolor' => '#17a2b8',
        'heading' => Html::encode($model->title),
        'description' => $model->excerpt,
        'card' => (isset($metas['include'])? '<div class="card-box text-light font-weight-light">'.Html::decode($metas['include']).'</div>' : false),
    ]) ?>
    <div class="container py-5">
        <?= frontend\widgets\Alert::widget() ?>
        <div class="entry-content"><?= $model->description ?></div>
        <div class="related mt-5">
            <?php
                if($model->pages) {
                    foreach($model->pages as $k=>$item) {
                        echo $this->render('block/_item', ['model' => $item]);
                    }
                }
            ?>
        </div>
    </div>
</article><!-- #page-## -->
