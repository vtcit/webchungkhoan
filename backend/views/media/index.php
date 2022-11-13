<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\LinkSorter;

use backend\assets\AppAsset;
use common\assets\FontAwesomeAsset;

AppAsset::register($this);
FontAwesomeAsset::register($this);

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

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_uploadmultiple'); ?>
<?php Pjax::begin(['id' => 'mediaList', 'timeout' => 10000]); ?>

<div class="media-<?= $type ?>view">
    <div class="action-toolbar row">
        <div class="actions col-md-4">
            <?= Html::button('<i class="glyphicon glyphicon-trash"></i> '.Yii::t('app', 'Delete Select'), ['id' => 'deleteBtn', 'class' => 'btn btn-danger', 'data' => ['pjax' => 0, 'loading-text' => '<i class="fas fa-spinner fa-spin"></i> '.Yii::t('app', 'Loading...')]]); ?>
        </div>
        <div class="col-md-8 search-form">
            <?= Html::a('<i class="fas fa-lg fa-border-all"></i>', ['media/index', 'type' => 'grid'], ['title' => Yii::t('app', 'Grid View'), 'class' => (($type=='grid')? 'btn disabled' : 'btn'), 'data' => ['toggle'=>'tooltip']]); ?><?= Html::a('<i class="fas fa-lg fa-list"></i>', ['media/index', 'type' => 'list'], ['title' => Yii::t('app', 'List View'), 'class' => (($type=='list')? 'btn disabled' : 'btn'), 'data' => ['toggle'=>'tooltip']]); ?>
            &nbsp;
            &nbsp;
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="filter-toolbar">
        <div class="row">
            <div class="col-md-2 select-all"><label for="selection_all"><input type="checkbox" id="selection_all" name="selection_all" value="1" /> <span><?= Yii::t('app', 'Check All') ?></span></label></div>
            <div class="col-md-10 sort-by">
                <span class="sort-by-txt"><?= Yii::t('app', 'Sort by') ?></span>
                <?= LinkSorter::widget([
                    'options' => [
                        'class' => 'list-inline',
                    ],
                    'sort' => $dataProvider->sort,
                    'attributes' => [
                        'title',
                        'filesize',
                        'mime_type',
                        // 'extension',
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php /*  $this->render('index-gridview', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) */ ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            // 'class' => 'clearfix',
        ],
        'layout' => "\n<ul class=\"clearfix media-list list-unstyled\">\n{items}\n</ul>\n<div class=\"text-center\">{pager}</div>",
        'itemView' => '_item',
        'itemOptions' => [ 'tag' => false ],
        //'pager' => ['options' => ['class' => 'text-center']]
    ]);
    ?>
</div>
<?php Pjax::end(); ?>
<?php
$deleteAction = json_encode(Url::to(['delete']));
$confirm = json_encode(Yii::t('yii', 'Are you sure you want to delete this item?'));
$script = <<<JS
    (function($){
        /* To initialize BS3 tooltips set this below */
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        /* To initialize BS3 popovers set this below */
        $('body').popover({selector: '[data-toggle="popover"]'});

        // $(document).on('pjax:send', function() {
            // $('.wrap').fadeOut(200);
        // });
        // $(document).on('pjax:complete', function() {
            // $('.wrap').fadeIn(200);
        // });

        var checkAll = "input[name='selection_all']";
        var inputs = "input[name='selection[]']";
        $('#mediaList').on('click', checkAll, function() {
            $('#mediaList').find(inputs).prop('checked', this.checked).change();
        });
        $('#mediaList').on('click', inputs, function() {
            var all = $('#mediaList').find(inputs).length == $('#mediaList').find(inputs + ":checked").length;
            $('#mediaList').find("input[name='selection_all']").prop('checked', all).change();
        });

        $(document).on('click', '#deleteBtn', function(event){

            event.preventDefault();
            selection = $('input[name="selection[]"]:checked').map( function(){ return this.value; }).get();

            if(selection.length == 0) return false;

            if(!confirm($confirm)) return false;

            deleteData = {
                [yii.getCsrfParam()]: yii.getCsrfToken(),
                id: selection
            };

            $.ajax({
                url: $deleteAction,
                type: 'POST',
                data: deleteData,
                beforeSend: function(xhr){
                    $('#deleteBtn').button('loading');
                },
                /* success: function (response){
                    if(typeof response == 'string')
                    {
                        response = JSON.parse(response);
                    }
                    if(response && typeof response.id == 'object')
                    {
                        $(response.id).each(function(idx, val)
                        {
                            // $('li#media_'+val+']').fadeOut(300, function(){ $(this).remove() });
                        });
                    }
                }, */
                error: function (xhr, status, error){
                    alert('HTTP Error: ' + error);
                    $('#deleteBtn').button('reset');
                }
            }).done(function(response){
                // DONE
                $('#deleteBtn').button('reset');
                $.pjax.reload({container: '#mediaList', async: false});
            }); //$.ajax
        });

    })(jQuery);
JS;
$this->registerJs($script);
//$this->registerCss($css);