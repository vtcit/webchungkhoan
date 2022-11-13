<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$img = '';
if($media = $model->imageFeatured)
{
    $sizes = json_decode($media->sizes);

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
}

if(!isset($tagHeading) || !preg_match('/^h[2-4]$/', $tagHeading))
    $tagHeading = 'h3';

$h_class = 'h6';
switch($tagHeading) {
    case 'h2':
        $h_class = 'h4';
        break;
    case 'h3':
        $h_class = 'h5';
        break;
}
?>

<article id="post-<?= $model->hashid ?>" class="hentry article-excerpt mb-4<?= isset($class)? ' '.Html::encode($class) : ''; ?>">
    <?php if($img): ?><div class="image"><?= Html::a($img, $model->url, ['class' => 'thumb resize', 'data' => ['pjax' => 0]]) ?></div><?php endif ?>
    <<?= $tagHeading ?> class="<?= $h_class ?> mt-1 mb-2 post-title entry-title"><?= Html::a($model->title, $model->url, ['data' => ['pjax' => 0]]) ?></<?= $tagHeading ?>>
    <div class="entry-meta mb-2 text-muted small">
        <strong class="vcard author d-none"><span class="fn"><?= ($model->author)? : (($model->user)? Inflector::camel2words($model->user->username) : Inflector::camel2words($_SERVER['SERVER_NAME'])) ?></span></strong>
        <time class="created_time font-italic" datetime="<?= Yii::$app->formatter->asDatetime($model->created_at) ?>" title="<?= Yii::$app->formatter->asDatetime($model->created_at, 'MM/dd/yyyy HH:mm') ?>"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></time>
        <?php if($model->cats): ?>
            <span class="tags ml-1">
                <?php foreach($model->cats as $k=>$cat): if($k==2) break; ?>
                    <?= Html::a('<span class="badge badge-info">'.$cat->title.'</span>', $cat->url, ['class' => 'd-inline-block m-1']) ?>
                <?php endforeach ?>
                <?php // tags ?>
            </span>
        <?php endif ?>
    </div><!-- entry-meta -->

    <div class="entry-content">
        <p class="excerpt"><?= Html::encode($model->excerpt) ?></p>
    </div>

    <div class="btns"><?= Html::a('<i class="fas fa-angle-right"></i> '.Yii::t('app', 'Read more'), $model->url, ['class' => 'btn btn-outline-info btn-sm py-0 px-2']) ?></div>

</article><!-- #post-## -->















