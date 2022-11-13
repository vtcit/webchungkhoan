<?php
use yii\helpers\Html;
use yii\helpers\Url;

$sizes = (array) json_decode($model->sizes);
$thumb = '';
if(isset($sizes['thumbnail']->file))
{
    $thumb = $sizes['thumbnail']->file;
}
$srcset = [];
$img = Yii::$app->urlManagerFrontend->createUrl(Yii::getAlias('@uploaddir').'/'.$thumb);
foreach($sizes as $k => $f)
{
    unset($sizes[$k]->filesize);
    $file = Yii::$app->urlManagerFrontend->createUrl(Yii::getAlias('@uploaddir').'/'.$f->file);
    $sizes[$k]->file = $file;
    $srcset[] = $file.' '.$f->width.'w';
}
$srcset = implode(',', $srcset);
?>
<li class="item clearfix" id="media_<?= $model->id ?>" role="checkbox" data-id="<?= $model->id ?>">
    <div class="media-checkbox">
        <?= Html::CheckBox('selection[]', false, [
            'label' => Html::img($img, ['alt' => $model->title, 'data' => ['sizes' => $sizes, 'srcset' => $srcset]]),
            // 'labelOptions' => ['class' => 'label_media'],
            'value' => $model->id,
        ]);
        ?>
        <span class="title m-info"><?= Html::a($model->title, ['media/update', 'id' => $model->id], ['data' => ['pjax' => 0]]) // ?></span>
    </div>
    <div class="filesize m-info"><?= Yii::$app->formatter->asShortSize(intval($model->filesize)) //human_filesize ?></div>
    <div class="mime_type m-info"><?= $model->mime_type ?></div>
</li>