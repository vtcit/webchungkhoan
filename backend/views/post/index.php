<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $this->params['type']->label);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Create new'), ['create', 'type' => $this->params['type']->name], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        $columns = [
            //['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($model){
                    $img = [];
                    foreach($model->media as $k => $media)
                    {
                        $thumbs = json_decode($media->sizes);
                        $img[] = Html::img(\Yii::$app->urlManagerFrontend->createUrl(\Yii::getAlias('@uploaddir/'.$thumbs->thumbnail->file)));
                        if($k==2)
                        {
                            break;
                        }
                    }
                    $img = implode(' ', $img);
                    return '<div class="post-thumbnail pull-left">'.$img.'</div>'.Html::a($model->title, ['post/update', 'id' => $model->id], ['data' => ['pjax' => 0]]);
                },
            ],
        ];
        foreach($availableCats as $k=>$catType)
        {
            $columns[] = [
                'attribute' => 'category',
                'label' => $catType->label,
                'format' => 'html',
                'filter' => Html::activeDropDownList($searchModel, 'category['.$catType->name.']', $catType->items, [ 'class' => 'form-control', 'id' => 'postsearch-category-'.$catType->name, 'prompt' => '' ]),
                'value' => function($model){
                    $html = [];
                    foreach($model->cats as $item)
                    {
                        // if($item->type != $catType->name) continue;
                        $html[] = $item->title;
                    }
                    return implode(', ', $html);
                },
            ];
        }
        $columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'contentOptions' => ['style' => 'width: 6%']];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
    <?php Pjax::end(); ?>
</div>

