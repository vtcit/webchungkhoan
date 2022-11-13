<?php

use yii\helpers\Html;
use yii\bootstrap4\Progress;
?>


<div id="upload-container">
    <?= Html::beginForm(['upload'], 'post', ['enctype' => 'multipart/form-data', 'class' => 'uploadForm', 'style' => 'padding: 20px 30px; border: 4px dashed #b4b9be; margin-bottom: 20px;']) ?>

        <div class="form-group field-file">
            <?= Html::fileInput('file[]', null, ['multiple' => true, 'accept' => 'image/*']) ?>
            <div class="help-block"></div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('<i class="glyphicon glyphicon-upload"></i> '.Yii::t('app', 'Upload'), ['class' => 'btn btn-success', 'data-textloading' => Html::encode(Yii::t('app', 'Uploading...'))]) ?>
        </div>

        <?= Progress::widget([
            'percent' => 0,
            'label' => 'test',
            'options' => ['class' => 'progress-striped', 'style' => 'display: none'],
        ]); ?>
        <!-- Preview-->
        <div class="preview"></div>
    <?= Html::endForm() ?>
</div><!-- upload-container -->
<script type="x-tmpl-mustache" id="media-item-temp">
    <li class="item item-just-uploaded clearfix" id="media_{{media_id}}" role="checkbox" data-id="{{media_id}}">
        <div class="media-checkbox">
            <label>
                <input type="checkbox" name="selection[]" value="{{media_id}}" disabled />
                <img src="{{image}}" alt="image" />
                <?= Progress::widget([
                    'percent' => 0,
                    'label' => 'test',
                    'options' => ['class' => 'progress-sm progress-striped'],
                ]); ?>
            </label>
            <span class="title m-info"><a href="{{url}}" data-pjax="0">{{title}}</a></span>
        </div>
        <div class="filesize m-info">{{filesize}}</div>
        <div class="mime_type m-info">{{mime_type}}</div>
    </li>
</script>
<?php
$script = <<< JS
(function($){
    var mediaItemTemp = $('#media-item-temp').html();
    var proccessPercentage = function(wrapId, d){
        progressBar = $(wrapId+' .progress-bar');
        progressBar.css('width', d+'%').attr('aria-valuenow', d).text(d+'%');
        return progressBar;
    };

    $('#upload-container form').submit(function (event) {
        event.preventDefault();
        var percentage = 0;
        uploadUrl = $(this).attr('action');
        if($('#upload-container form input[type=file]').val())
        {
            var files_data = $('#upload-container form input[type=file]').prop('files');
            // var form_data = new FormData($(this)[0]);
            var match = ["image/gif", "image/png", "image/jpeg",];
            var allProccessId = '#upload-container .progress';
            var totalSize = 0;
            var ajaxArr = [];

            $('#upload-container .form-group.field-file').removeClass('has-error');
            $('#upload-container .form-group.field-file .help-block').text('');
            proccessPercentage(allProccessId, percentage).addClass('active');
            $(allProccessId).fadeIn();
            $('#upload-container button[type=submit]').attr('disabled', true);

            $(files_data).each(function(idx, file_data)
            {
                totalSize += file_data.size;
                html = Mustache.render(mediaItemTemp, {
                    media_id: 'u_'+idx,
                    image : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
                    url: "#"
                });
                $('#mediaList ul.media-list').prepend(html);
            });

            $(files_data).each(function(idx, file_data)
            {
                var type = file_data.type;
                var aProccessId = '#media_u_'+idx+' .progress';
                proccessPercentage(aProccessId, 0).addClass('active');
                if($.inArray(type, match)!= -1)
                {
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    ajaxArr.push(
                        $.ajax({
                            url: uploadUrl,
                            dataType: 'text',
                            type: 'POST',
                            data: form_data,
                            // async: true,
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function(xhr){
                            },
                            xhr: function () {
                                var myXhr = $.ajaxSettings.xhr();
                                myXhr.upload.addEventListener('progress', function(evt) {
                                    if (evt.lengthComputable) {
                                        _percentage = Math.ceil( percentage + evt.loaded*file_data.size*100/evt.total/totalSize );
                                        proccessPercentage(allProccessId, _percentage);

                                        i_percentage = Math.ceil(evt.loaded*100/evt.total);
                                        proccessPercentage(aProccessId, i_percentage);
                                    }
                                }, false);

                                return myXhr;
                            },
                            /*
                            success: function (response){
                            }, 
                            */
                            error: function (xhr, status, error){
                                alert('HTTP Error: ' + status);
                            }
                        }).done(function(response){
                            $(aProccessId).remove();

                            percentage += file_data.size*100/totalSize;
                            proccessPercentage(allProccessId, Math.ceil(percentage));

                            json = JSON.parse(response);
                            if(response && typeof json.error == 'string')
                            {
                                $('#upload-container .form-group.field-file').addClass('has-error');
                                $('#upload-container .help-block').append( '<div class="item">'+json.error+'</div>' );
                                return false;
                            }
                            $('#media_u_'+idx+' img').attr('src', json.location);
                            $('#media_u_'+idx+' .title a').attr('href', json.url).text(json.title);
                            $('#media_u_'+idx+' .filesize').text(json.filesize);
                            $('#media_u_'+idx+' .mime_type').text(json.mime_type);
                            $('#media_u_'+idx+' input[type=checkbox]').removeAttr('disabled').val(json.id);
                            // Must last action
                            $('#media_u_'+idx).attr('data-id', json.id).attr('id', 'media_'+json.id);

                        }) //$.ajax
                    ); // push ajaxArr
                } //if $.inArray(type, match)
            }); //$(files_data).each
            
            $.when.apply(undefined, ajaxArr).done(function(){
                $(allProccessId+' .progress-bar').removeClass('active');
                $('#upload-container form input[type=file]').val('');
                $('#upload-container button[type=submit]').removeAttr('disabled');
                setTimeout(function(){
                    $(allProccessId).fadeOut();
                }, 5000);
                // $.pjax.reload({container: '#mediaList', async: false});
            }).fail(function(){
                // something went wrong here, handle it
            });
        }
        return false;
    });
})(jQuery);
JS;
$this->registerJs($script);