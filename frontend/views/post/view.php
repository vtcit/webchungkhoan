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
$this->params['metatag']['published_time'] = $model->created_at;
$this->params['metatag']['modified_time'] = ($model->updated_at? : $model->created_at);
$this->params['metatag']['updated_time'] = ($model->updated_at? : $model->created_at);
$this->params['metatag']['description'] = $model->excerpt;

if($model->type != 'post') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->typeObj->label), 'url' => ['index', 'type' => $model->type]];
}
foreach($model->cats as $k=>$cat)
{
    if($k==2) break;
    if($cat->parent)
    {
        $this->params['breadcrumbs'][] = ['label' => $cat->parent->title, 'url' => $cat->parent->url];
    }
    $this->params['breadcrumbs'][] = ['label' => $cat->title, 'url' => $cat->url];

}
// $this->params['breadcrumbs'][] = $this->title;

$img = '';
if($media = $model->imageFeatured)
{
    $sizes = json_decode($media->sizes);
    if(isset($sizes->large))
    {
        if($thumb = $sizes->large)
        {
            $this->params['metatag']['image'] = Url::to(['@uploaddir/'.$thumb->file], true);
            $this->params['metatag']['image_width'] = $thumb->width;
            $this->params['metatag']['image'] = $thumb->height;
        }
    }

    if(isset($sizes->medium->file))
    {
        $img = Url::to(['@uploaddir/'], true).$sizes->medium->file;
    }

    $srcset = [];
    foreach($sizes as $k => $f)
    {
        // unset($sizes->{$k}->filesize);
        $file = Url::to(['@uploaddir/'], true).$f->file;
        // $sizes->{$k}->file = $file;
        $srcset[] = $file.' '.$f->width.'w';
    }

    $srcset = implode(',', $srcset);
    $img = Html::img($img, ['alt' => $model->title, 'data' => ['srcset' => $srcset]]); //'sizes' => json_encode($sizes), 
    $card = $img;
}
?>
<article id="post-<?= $model->hashid ?>" class="hentry article-detail">
    <?= $this->render('/masthead', [
        // 'bgcolor' => '#17a2b8',
        'heading' => Html::encode($model->title),
        'description' => $model->excerpt,
        'card' => isset($card)? $card : false,
    ]) ?>
    <div class="container py-5">
        <?= frontend\widgets\Alert::widget() ?>
        <div class="row">
            <div class="col-xs-12 col-md-8 main-col">
                <div class="entry-content">
                    <p class="entry-meta small clearfix">
                        <strong><span class="vcard author d-none"><span class="fn"><?= ($model->author)? : 'songtudo.com' ?></span></span></strong> <i class="created_time" title="<?= Yii::$app->formatter->asDatetime($model->created_at) ?>"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></i>
                        <?php if($model->cats): ?>
                            &nbsp;
                            <span class="tags">
                                <?php foreach($model->cats as $cat): if($k==2) break; ?>
                                    <?= Html::a('<span class="badge badge-info">'.$cat->title.'</span>', $cat->url) ?>
                                <?php endforeach ?>
                                <?php // tags ?>
                            </span>
                        <?php endif ?>
                    </p><!-- entry-meta -->
                    <?= $model->description ?>
                    </div>
                <hr>
                <div class="related mt-5">
                    <?= $this->render('/post/block/block-4-2', ['cat' => Category::findOne(['slug' => 'kinh-doanh']), 'posts' => $posts]) ?>
                </div>
            </div>
            <div class="d-none d-sm-block col-md-4 right-col">
                <?= $this->render('/post/block/block-list', ['cat' => Category::findOne(['slug' => 'giai-tri']), 'posts' => $posts]) ?>
                <?= $this->render('/post/block/block-4', ['cat' => Category::findOne(['slug' => 'tu-van']), 'posts' => $posts]) ?>
            </div>
        </div>
    </div>
</article><!-- #post-## -->
