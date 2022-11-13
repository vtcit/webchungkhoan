<?php
use yii\helpers\Url;
use yii\helpers\Html;

if(!function_exists('_getThumb')) {
    function _getThumb($model) {
        
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
            $img = Html::img($img, ['class' => 'img-fluid lazy', 'alt' => $model->title, 'data' => ['srcset' => $srcset]]); //'sizes' => json_encode($sizes), 
        }
        return $img;

    }
}

if(!function_exists('_getAuthor')) {
    function _getAuthor($model) {
        return $model->author? : ($model->user? Inflector::camel2words($model->user->username) : Inflector::camel2words($_SERVER['SERVER_NAME']));
    }
} ?>

<div class="widget mb-2 clearfix">
    <h3 class="h5 h-title">
        <a href="<?= $cat->url ?>"><span><?= $cat->title ?></span></a>
    </h3>
    <ul class="post-list">
        <?php foreach($posts as $k => $model) { ?>
        <li><a href="<?= $model->url ?>"><?= $model->title ?></a></li>
    <?php } ?>
    </ul>
</div>
