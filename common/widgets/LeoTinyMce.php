<?php

namespace common\widgets;
use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This extends TinyMce with customize
 *  @author Leo
 */
class LeoTinyMce extends \dosamigos\tinymce\TinyMce
{
    
    public function init()
    {
        $this->options = ['rows' => 20];
        $this->language = Yii::$app->language;

        parent::init();

        $this->clientOptions = ArrayHelper::merge([
            'branding' => false,
            'entity_encoding' => 'raw',
            'content_css' => Url::to(['css/custom-tinymce-editor.css?v=1']),
            'relative_urls' => false, // true default
            'remove_script_host' => false, // true default
            'icons_url' => Yii::$app->urlManagerFrontend->createUrl('myTinymce/icons/vusicon/icons.js?v=13'),
            'icons' => 'vusicon',      // use icon pack
            'external_plugins' => [
                'readmore' => Yii::$app->urlManagerFrontend->createUrl('myTinymce/plugins/readmore/plugin.js?v=13'),
                // 'youtube' => Yii::$app->urlManagerFrontend->createUrl('myTinymce/plugins/youtube/plugin.min.js?v=1', true),
            ],
            'plugins' => 'preview paste searchreplace autolink  code fullscreen image link media table hr pagebreak nonbreaking advlist lists wordcount textpattern noneditable charmap emoticons',
            'toolbar' => [ // strikethrough alignjustify
                'formatselect bold italic underline superscript subscript | alignleft aligncenter alignright | bullist numlist | outdent indent | link unlink| pastetext searchreplace | fullscreen',
                'undo redo | fontsizeselect | forecolor backcolor removeformat | table media image readmore hr charmap emoticons youtube blockquote | preview code',
            ],
        ], $this->clientOptions);
    }
/*
    public function run()
    {
        $mediaUrl = Url::to(['media/index', 'type' => 'modal']);
        echo Html::button('<i class="fas fa-image"></i> '.Yii::t('app', 'Add Media'), [
            'id' => 'insertMediaEditor',
            'class'=>'btn btn-sm btn-primary' ,
        ]);
        Parent::run();
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }
    */
}