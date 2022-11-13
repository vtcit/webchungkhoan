<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Media');
$this->params['breadcrumbs'][] = $this->title;

function human_filesize($size, $precision = 2) {
    static $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $step = 1024;
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }
    return round($size, $precision).$units[$i];
}

?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_uploadmultiple'); ?>

    <?php Pjax::begin(['id' => 'mediaList', 'timeout' => 10000]); ?>

        <?= Html::beginForm(['delete'], 'post', ['id' => 'deleteForm', '']); ?>

            <div class="form-inline toolbar">
                <?= Html::submitButton('<i class="glyphicon glyphicon-trash"></i> '.Yii::t('app', 'Delete'), ['id' => 'deleteBtn', 'class' => 'btn btn-danger']); ?>
            </div>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $column){
                            return ['class' => 'checkbox-column'];
                        }
                    ],

                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function($model){
                            $sizes = json_decode($model->sizes);
                            $img = '';
                            if(isset($sizes->thumbnail->file))
                            {
                                $thumb = $sizes->thumbnail->file;
                                $img = Html::img(Yii::$app->urlManagerFrontend->createUrl(Yii::getAlias('@uploaddir').'/'.$thumb), ['style' => 'width: 50px; height:auto;float:left;margin-right: 10px;']);
                            }

                            return '<strong>'.Html::a($img.$model->title, ['media/update', 'id' => $model->id], ['data' => ['pjax' => 0]]).'</strong><br /><small><i>'.$model->file.'</i></small>';
                        },
                    ],
                    //'user_id',
                    //'file',
                    [
                        'attribute' => 'filesize',
                        'value' => function($model){
                            return human_filesize($model->filesize);
                        },
                    ],
                    // 'mime_type',
                    [
                        'attribute' => 'mime_type',
                        // 'label' => Yii::t('yii', 'Type'),
                        'filter' => Html::activeDropDownList($searchModel, 'mime_type', ['' => 'All', 'image/jpeg' => '.jpg', 'image/png' => '.png'], [ 'class' => 'form-control', 'data-live-search' => 'true' ]),
                        'value' => 'mime_type',
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        <?php Html::endForm(); ?>
    <?php Pjax::end(); ?>

</div>
<script type="x-tmpl-mustache" id="progress-bar">
    <div class="progress-wrap" style="height: {{percentage}}px">
        <div class="progress">
            <div class="progress-bar progress-bar-striped" role="progressbar"
                aria-valuenow="{{percentage}}" aria-valuemin="0" aria-valuemax="100" style="width:{{percentage}}%">
            </div>
        </div>
    </div>
</script>
<?php
$script = <<<JS
    (function($){
        // proccessbarTemp = $('#progress-bar').html();
        // html = Mustache.render(proccessbarTemp, {percentage: 50});
        // $('.loading').html(html);
/* 
        $(document).on('pjax:send', function() {
            //$('.wrap').fadeOut(200);
        });
        $(document).on('pjax:complete', function() {
            //$('.wrap').fadeIn(200);
        });
        $('#deleteForm').on('submit', function(event){
            event.preventDefault();
            var keys = $('#mediaList table').yiiGridView('getSelectedRows');
            data = [
            ];
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                // data: data,
                data: $(this).find('input[name=_csrf-backend], input.checkbox-column').serialize(),
                // success: function (response){
                    // if(typeof response == 'string')
                    // {
                        // response = JSON.parse(response);
                    // }
                    // if(response && typeof response.id == 'object')
                    // {
                        // $(response.id).each(function(idx, val)
                        // {
                             // $('tr[data-key='+val+']').fadeOut(300, function(){ $(this).remove() });
                        // });
                    // }
                // },
                error: function (xhr, status, error){
                    alert('HTTP Error: ' + error);
                }
            }).done(function(response){
                // DONE
                $.pjax.reload({container: '#mediaList'});
            }); //$.ajax
        });
 */
    })(jQuery);

JS;
$this->registerJs($script);
//$this->registerCss($css);