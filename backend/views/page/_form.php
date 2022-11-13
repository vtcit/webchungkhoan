<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\LeoTinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */

$availablePages = $model->getAvailablePages()->where(['parent_id' => null, 'type' => $this->params['type']->name])->andWhere(['<>', 'id', intval($model->id)])->all();
if($availablePages)
{
    $availablePages = \yii\helpers\ArrayHelper::map($availablePages , 'id', 'title');
}

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
$slug = $model->slug? : '-';
$htmlSlug = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['page/index', 'type' => $this->params['type']->name]).'/<code id="txtSlug">'.($slug=='-'? '_(auto)' : $slug).'</code> &nbsp; '.Html::a(Yii::t('app', 'Edit'), 'javascript:;', ['id' => 'editSlugBtn', 'data-toggle' => 'collapse', 'data-target' => '#field-post-slug']);
?>

<?php $form = ActiveForm::begin(['options' => ['class' => 'page-form']]); ?>
<?= $form->errorSummary($model) ?>
<div class="row">
    <div class="col-lg-9 col-md-8">

        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->hint('</div><div class="slug-url" style="font-weight: normal;">Link: '.$htmlSlug ) ?>

        <?= $form->field($model, 'slug', [
            'options' => [
                'id' => 'field-post-slug',
                'class' => 'collapse field-post-slug'
            ]
        ])->textInput([
            'maxlength' => true,
            'class' => 'form-control',
            'placeholder' => Yii::t('app', 'Will be automatically generated')
        ])->hint('Please only use letters: [a-z0-9-]') ?>

        <?= $form->field($model, 'type')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'parent_id')->dropDownList($availablePages, ['prompt'=>'- No Parent -']) ?>

        <?= $form->field($model, 'excerpt')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'description')->widget(LeoTinyMce::className())->label($model->attributeLabels()['description'].'<br />'.$insertMediaEditor); ?>

        <?php if(isset($this->params['type']->attributes)) {
            foreach($this->params['type']->attributes as $k => $att_group) {
                $att_group = (object) $att_group;
        ?>
            <div id="att_group<?= $k ?>">
                <?php if($att_group->label) echo '<h4>'.$att_group->label.'</h4>'; ?>
                <?php foreach($att_group->items as $item) {
                    $item = (object) $item;
                    switch($item->type)
                    {
                        case 'number':
                        case 'password':
                        case 'tel':
                        case 'email':
                            echo $form->field($model, "meta[$item->name]")->input($item->type)->label($item->label);
                            break;
                        case 'checkbox':
                            if(isset($model->meta[$item->name]))
                            {
                                $model->meta[$item->name] = json_decode($model->meta[$item->name]);
                            }
                            echo $form->field($model, "meta[$item->name]")->checkboxList($item->options)->label($item->label);
                            break;
                        case 'radio':
                            echo $form->field($model, "meta[$item->name]")->radioList($item->options)->label($item->label);
                            break;
                        case 'select':
                            echo $form->field($model, "meta[$item->name]")->dropDownList($item->options)->label($item->label);
                            break;
                        case 'multi-select':
                            if(isset($model->meta[$item->name]))
                            {
                                $model->meta[$item->name] = json_decode($model->meta[$item->name]);
                            }
                            echo $form->field($model, "meta[$item->name]")->listBox($item->options, ['multiple' => true])->label($item->label);
                            break;
                        case 'textarea':
                            echo $form->field($model, "meta[$item->name]")->textarea(['rows' => 5])->label($item->label);
                            break;
                        case 'editor':
                            echo $form->field($model, "meta[$item->name]")->widget(LeoTinyMce::className())->label($item->label);
                            break;
                        case 'media':
                            echo $form->field($model, "meta[$item->name]")->hiddenInput()->label($item->label)->hint('<div class="preview media_0 text-center text-ellipsis">Image</div> <br />'.Html::button('<i class="fas fa-plus"></i> '.Yii::t('app', 'Add Media'), ['class' => 'btn btn-sm btn-default']));
                            break;
                        default:
                            echo $form->field($model, "meta[$item->name]")->textInput()->label($item->label);
                            break;
                    } // switch
                } //foreach ($att_group->items) ?>
            </div>
        <?php
            } //foreach($this->params['type']->attributes)
        } // if(isset($this->params['type']->attributes)) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <div class="col-lg-3 col-md-4">
        <div class="form-group field-page-media_ids">
            <label><?= $model->attributeLabels()['media_ids'] ?></label>
            <p><?= $insertMediaImage ?></p>
            <ul class="images sortable list-unstyled clearfix">
            <?php
                $postImage = [];
                foreach($model->media as $media)
                {
                    $sizes = json_decode($media->sizes);
                    if(isset($sizes->thumbnail->file))
                    {
                        $img = Yii::$app->urlManagerFrontend->createUrl(Yii::getAlias('@uploaddir').'/'.$sizes->thumbnail->file);
            ?>

                <li data-id="<?= $media->id ?>">
                    <img src="<?= Html::encode($img) ?>" alt="image" />
                    <button type="button" class="btn btn-sm btn-danger btnDel" title="delete"><i class="fas fa-times"></i></button>
                    <input type="hidden" id="page-media_ids-<?= $media->id ?>" name="Page[media_ids][]" value="<?= $media->id ?>">
                </li>
            <?php
                    }
                }
            ?>
            </ul>
            <div class="help-block"><?= Yii::t('app', 'Click and drag to reorder images.') ?></div>
        </div>
    </div>
</div><!-- row -->
<?php ActiveForm::end(); ?>
<!-- Modal -->
<div class="modal fade" id="mediaManager" tabindex="0" role="dialog" aria-labelledby="mediaManagerLabel">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mediaManagerLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <!-- <iframe src="" frameborder="0" width="100%"></iframe> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary modalAction">Insert</button>
      </div>
    </div>
  </div>
</div>

<script type="x-tmpl-mustache" id="insertImageTemp">
    <li data-id="{{id}}">
        <img src="{{img}}" alt="image" />
        <button type="button" class="btn btn-sm btn-danger btnDel" title="delete"><i class="glyphicon glyphicon-remove"></i></button>
        <input type="hidden" id="page-media_ids-{{id}}" name="Page[media_ids][]" value="{{id}}">
    </li>
</script>
<?php
$script = <<< JS
var insertImageTemp = $('#insertImageTemp').html();
var slugged = ($('input#page-slug').val() != '');
$(function () {
    $('.sortable').sortable();
    // $('.sortable').disableSelection();

    $('input#page-slug').keyup(delay(function(event){
        slugged = true;
        doSlug($(this).val());
    }, 500));

    $('#page-title').keyup(delay(function(event){
        if($('input#page-slug').val() == '' || !slugged)
            doSlug($(this).val());
    }, 2000));

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

    var insertImageModal = false;
    $('#insertMediaImage').click(function(){
        $('.modalAction').attr('id', 'insertImage');
        $('#mediaManager').modal('show');
        if(!insertImageModal)
        {
            insertImageModal = true;
            $('#mediaManager').find('.modal-body').load('$mediaUrl'); //prop
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

    $('#mediaManager').on('click', '#insertImage', function() {
        _html = '';
        _ids = $('.images input[name="Page[media_ids][]"]').map( function(){ return this.value; }).get() || [];
        $('#mediaList input[name="selection[]"]:checked').each(function(idx, item){
            // Check unique image
            if((_ids.length > 0) && ($.inArray(item.value, _ids) != -1))
                return;

            _ids.push(item.value);
            _html += appendImage(item.value, $('#media_'+item.value+' img').attr('src'));
        });
        $('.field-page-media_ids .images').append(_html);
        $('#mediaManager').modal('hide');
    });
    $('.field-page-media_ids').on('click', '.btnDel', function(){
        $(this).parent('li').remove();
    });

});
function appendImage(_id, _img) {
    return Mustache.render(insertImageTemp, {
        id: _id,
        img: _img
    });
}

function doSlug(str) {
    str = slug(str);
    $('input#page-slug').val(str);
    $('code#txtSlug').text(str);
    return true;
}

function insertMedia(sHTML) {
    tinymce.activeEditor.insertContent(sHTML);
}

function closeMedia() {
    tinymce.activeEditor.windowManager.close();
}

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

function slug(str) {

    // Chuyển hết sang chữ thường
    str = str.toLowerCase();
 
    // xóa dấu
    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    str = str.replace(/(đ)/g, 'd');
 
    // Xóa ký tự đặc biệt
    // Xóa khoảng trắng đầu, cuối
    // Chuyển về khoảng trắng đơn và thay bằng ký tự -
    str = str.replace(/([^0-9a-z-\s])/g, '').trim().replace(/(\s+)/g, '-');
    // 
    // Chuyển về Chuyển về khoảng trắng đơn đơn
    str = str.replace(/(-+)/g, '-');

    // return
    return str;
}

function tinymcePickerCallback(callback, value, meta) {
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
    .images li
    {
        float: left;
        width: 87px;
        padding: 5px;
        position: relative;
    }
    .images img
    {
        max-width: 100%;
    }
    .images .btnDel
    {
        position: absolute;
        top: 0;
        right: 0;
        line-height: 1;
        padding: 2px 3px;
    }
CSS;
$this->registerJs($script);
$this->registerCss($css);
