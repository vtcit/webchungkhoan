<!-- start block-2 -->
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
}
?>
<div class="block-area block-2 mb-2 clearfix">
    <h3 class="h5 h-title">
        <a href="<?= $cat->url ?>"><span><?= $cat->title ?></span></a>
    </h3>
    <div class="big-post">
        <?php $model = array_shift($posts); ?>
        <article class="mb-4">
            <div class="image-wrapper">
                <a href="<?= $model->url ?>" class="thumb resize pt-golden">
                    <?= _getThumb($model) ?>
                </a>
            </div>
            <div class="post-body">
                <h2 class="h4 mt-1 mb-2 post-title">
                    <a href="<?= $model->url ?>"><?= $model->title ?></a>
                </h2>
                <!-- author, date and comments -->
                <div class="mb-2 text-muted small">
                    <span class="d-none d-sm-inline mr-1">
                        <a class="font-weight-bold" href="#"><?= _getAuthor($model) ?></a>
                    </span>
                    <time datetime="<?= Yii::$app->formatter->asDatetime($model->created_at) ?>"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></time>
                    <span title="9 comment" class="float-right">
                        <span class="fa fa-comments" aria-hidden="true"></span> 9
                    </span>
                </div>
                <!--description-->
                <p><?= Html::encode($model->excerpt) ?></p>
            </div>
        </article>
    </div> <!--big-post-->

    <div class="small-post">
        <?php foreach($posts as $k => $model) {
            if($k==4) break;
        ?>
            <article class="mb-4">
                <div class="row">
                    <div class="col-3 col-md-4 pr-2 pr-md-0">
                        <div class="image-wrapper">
                            <a href="<?= $model->url ?>" class="thumb resize">
                                <?= _getThumb($model) ?>
                            </a>
                        </div>
                    </div>
                    <!-- title & date -->
                    <div class="col-9 col-md-8">
                        <h4 class="h6 h5-sm h6-lg post-title">
                            <a href="<?= $model->url ?>"><?= $model->title ?></a>
                        </h4>
                        <div class="small text-muted">
                        <time datetime="<?= Yii::$app->formatter->asDatetime($model->created_at) ?>"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></time>
                        </div>
                    </div>
                </div>
            </article>
        <?php } ?>
    </div> <!-- small-post -->
</div> <!-- block-2 -->
