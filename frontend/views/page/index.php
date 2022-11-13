<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $this->params['type']->label).' - Vustech';
$this->params['metatag']['url'] = Url::to(['page/index', 'type' => $type], true);
$this->params['metatag']['type'] = 'article';
$this->params['metatag']['description'] = 'Các bài viết về '.$this->title;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'template' => "<li class=\"breadcrumb-item d-none\">{link}</li>\n"];
?>
<?= $this->render('/masthead', [
    'bgcolor' => '#17a2b8',
    'heading' => Html::encode($this->title),
    'description' => null,
]) ?>
<div class="container py-5">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model->title), ['view', 'slug' => $model->slug]);
        },
    ]) ?>
</div>
