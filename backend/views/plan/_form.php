<?php

use common\widgets\LeoTinyMce;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Plan $model */
/** @var yii\widgets\ActiveForm $form */


$uploadUrl = Url::to(['media/upload', 'type' => 'ajax', 'json' => true]);
$mediaUrl = Url::to(['media/index', 'type' => 'modal']);
$insertMediaEditor = Html::button('<i class="fas fa-image"></i> '.Yii::t('app', 'Add Media'), [
    'id' => 'insertMediaEditor',
    'class'=>'btn btn-sm btn-primary' ,
]);
$insertMediaImage = Html::button('<i class="fas fa-image"></i> '.Yii::t('app', 'Add Image'), [
    'id' => 'insertMediaImage',
    'class'=>'btn btn-sm btn-primary' ,
]);
?>

<div class="plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'description')->widget(LeoTinyMce::className())->label($model->attributeLabels()['description'].'<br />'.$insertMediaEditor); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'desc_short')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'during_time')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

$script = <<< JS

$(function () {
    var insertEditorModal = false;
    $('#insertMediaEditor').click(function(){
        $('.modalAction').attr('id', 'insertEditor');
        $('#mediaManager').modal('show');
        if(!insertEditorModal)
        {
            $('#mediaManager').find('.modal-body').load('{$mediaUrl}'); //prop
            insertEditorModal = true;
        }
    });
    $('#mediaManager').on('click', '#insertEditor', function() {
        imgs = $('#mediaList input[name="selection[]"]:checked').map( function(){
            _img = $('#media_'+this.value+' img');
            _sizes = JSON.parse($(_img).attr('data-sizes'));
            _title = $(_img).attr('alt');
            _img = _sizes.large.file;
            _w = _sizes.large.width;
            _h = _sizes.large.height;
            return '<img src="'+_img+'" width="_w" height="_h" alt="'+_title+'" />';
        }).get();
        tinymce.activeEditor.insertContent(imgs.join(' '));
        $('#mediaManager').modal('hide');
    });
});
function insertMedia(sHTML){
    tinymce.activeEditor.insertContent(sHTML);
}

function closeMedia() {
    tinymce.activeEditor.windowManager.close();
}
function tinymcePickerCallback(callback, value, meta)
{
    $('#mediaManager').click();
    //window.open('{$mediaUrl}','','width=100%,height=540 ,toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no');
}

function tinymceUploadHandler(blobInfo, success, failure) {

    formData = new FormData();
    formData.append(yii.getCsrfParam(), yii.getCsrfToken());
    formData.append('file', blobInfo.blob(), blobInfo.filename());
    $.ajax({
        url: '{$uploadUrl}',
        dataType: 'text',
        type: 'post',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            json = JSON.parse(response);
            if(!response && typeof json.error != 'string')
            {
                failure('Invalid JSON! Error: ' + json.error);
                return;
            }
            success(json.location);
        },
        error: function (xhr, textStatus) {
            failure('HTTP Error: ' + textStatus);
            return;
        }
    }); //$.ajax
}
JS;

$css = <<< CSS
CSS;
$this->registerJs($script);
$this->registerCss($css);