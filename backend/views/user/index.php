<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],

            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a(($model->display_name? : $model->username), ['user/view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'roles',
                // 'format' => 'raw',
                'value' => function($model){
                    if($roles = $model->getRoles()) {
                        $html = [];
                        foreach($roles as $role)
                        {
                            $html[] = Yii::t('app', ucfirst($role->name));
                        }
                        return implode(', ', $html);
                    }
                },
            ],
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            //'status',
            // 'created_at',
            //'updated_at',
            // [
            //     'attribute' => 'created_at',
            //     'value' => function($model) {
            //         return Yii::$app->formatter->asDatetime($model->created_at, 'MM/dd/yyyy HH:mm');
            //     },
            // ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model){
                    return $model::getStatuses()[$model->status];
                },
            ],

            // ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}', 'contentOptions' => ['style' => 'width: 6%']],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update} &nbsp; {viewTransaction} &nbsp; {delete}',
                'buttons' => [
                    // 'view' => function ($url, $model) {
                    //     return Html::a('<i class="fa fa-arrow-right"></i> View', $url, ['class' => 'btn btn-sm btn-info']);
                    // },
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'Sửa'), $url, ['class' => 'btn btn-sm btn-info']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'Xóa'), $url, ['class' => 'btn btn-sm btn-danger']);
                    },
                    'viewTransaction' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'Xem giao dịch'), ['transaction', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']);
                    },
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
