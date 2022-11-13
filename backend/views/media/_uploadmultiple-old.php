<?php

use yii\helpers\Html;
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

        <div class="loading"></div>
        <div class="progress-wrap" style="height: 40px;">
            <div class="progress" style="display: none;">
                <div class="progress-bar progress-bar-striped" role="progressbar"
              aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                <!-- Loading % -->
                </div>
            </div>
        </div>
        <!-- Preview-->
        <div class="preview"></div>
    <?= Html::endForm() ?>
</div><!-- upload-container -->
<?php
$script = <<< JS
(function($){
    $('#upload-container form').submit(function (event) {
        event.preventDefault();
        var percentage = 0;
        uploadUrl = $(this).attr('action');
        if($('#upload-container form input[type=file]').val())
        {
            var files_data = $('#upload-container form input[type=file]').prop('files');
            // var form_data = new FormData($(this)[0]);
            var match = ["image/gif", "image/png", "image/jpeg",];

            $('#upload-container .form-group.field-file').removeClass('has-error');
            $('#upload-container .form-group.field-file .help-block').text('');
            $('#upload-container .progress').fadeIn();
            $('#upload-container .progress-bar').css('width', percentage+'%').attr('aria-valuenow', percentage).text(percentage+'%').addClass('active');

            $('#upload-container button[type=submit]').attr('disabled', true);

            totalSize = 0;
            $(files_data).each(function(idx, file_data)
            {
                totalSize += file_data.size;
            });
            var ajaxArr = [];
            $(files_data).each(function(idx, file_data)
            {
                var type = file_data.type;
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
                                        // percentage = Math.ceil(evt.loaded*100/evt.total);
                                        _percentage = Math.ceil( percentage + evt.loaded*file_data.size*100/evt.total/totalSize );
                                        $('#upload-container .progress-bar').css('width', _percentage+'%').attr('aria-valuenow', _percentage).text(_percentage+'%');
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
                            percentage += file_data.size*100/totalSize;
                            _percentage = Math.ceil(percentage);
                            $('#upload-container .progress-bar').css('width', _percentage+'%').attr('aria-valuenow', _percentage).text(_percentage+'%');

                            json = JSON.parse(response);
                            if(response && typeof json.error == 'string')
                            {
                                $('#upload-container .form-group.field-file').addClass('has-error');
                                $('#upload-container .help-block').append( '<div class="item">'+json.error+'</div>' );
                                return false;
                            }

                        }) //$.ajax
                    ); // push ajaxArr
                } //if $.inArray(type, match)
            }); //$(files_data).each
            
            $.when.apply(undefined, ajaxArr).done(function(){
                $('#upload-container .progress-bar').removeClass('active');
                $('#upload-container form input[type=file]').val('');
                $('#upload-container button[type=submit]').removeAttr('disabled');
                $('#upload-container .progress').delay(5000, function(){
                    $(this).fadeOut();
                    // percentage = _percentage = 0;
                    // $('#upload-container .progress-bar').css('width', _percentage+'%').attr('aria-valuenow', _percentage).text(_percentage+'%');
                });
                $.pjax.reload({container: '#mediaList', async: false});
            }).fail(function(){
                // something went wrong here, handle it
            });
        }
        return false;
    });
})(jQuery);
JS;
$this->registerJs($script);