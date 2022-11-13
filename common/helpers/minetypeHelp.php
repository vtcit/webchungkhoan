<?php

class MimeTypeHelper
{
    public $mimetypes = [
        'image/*' => Yii::('yii', 'Image'),
        'audio/*' => Yii::('yii', 'Audio'),
        'video/*' => Yii::('yii', 'Video'),
        'application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-word.document.macroEnabled.12,application/vnd.ms-word.template.macroEnabled.12,application/vnd.oasis.opendocument.text,application/vnd.apple.pages,application/pdf,application/vnd.ms-xpsdocument,application/oxps,application/rtf,application/wordperfect,application/octet-stream' => Yii::('yii', 'Document'),
        'application/vnd.apple.numbers,application/vnd.oasis.opendocument.spreadsheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroEnabled.12,application/vnd.ms-excel.sheet.binary.macroEnabled.12' => Yii::('yii', 'Sheet'),
        'application/x-gzip,application/rar,application/x-tar,application/zip,application/x-7z-compressed' => Yii::('yii', 'Archive'),
    ];

    public static function getName($mimetype)
    {
        
    }
}