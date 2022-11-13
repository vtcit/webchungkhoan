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

<article id="page-<?= $model->hashid ?>" class="hentry article-excerpt mb-4<?= isset($class)? ' '.Html::encode($class) : ''; ?>">
    <?php if($img): ?><div class="image"><?= Html::a($img, $model->url, ['class' => 'thumb resize', 'data' => ['pjax' => 0]]) ?></div><?php endif ?>
    <<?= $tagHeading ?> class="<?= $h_class ?> mt-1 mb-2 page-title entry-title"><?= Html::a($model->title, $model->url, ['data' => ['pjax' => 0]]) ?></<?= $tagHeading ?>>
    <div class="entry-meta mb-2 text-muted small d-none">
        <strong class="vcard author d-none d-sm-inline"><span class="fn"><?= Inflector::camel2words($_SERVER['SERVER_NAME']) ?></span></strong>
    </div><!-- entry-meta -->

    <div class="entry-content">
        <p class="excerpt"><?= Html::encode($model->excerpt) ?></p>
    </div>

    <div class="btns"><?= Html::a('<i class="fas fa-angle-right"></i> '.Yii::t('app', 'Read more'), $model->url, ['class' => 'btn btn-outline-info btn-sm py-0 px-2']) ?></div>

</article><!-- #post-## -->















