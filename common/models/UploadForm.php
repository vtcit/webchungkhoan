<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\Media;

class UploadForm extends Model
{
    /**
     * @var fileUpload
     */
    public $fileUpload;

    public function rules()
    {
        return [
            [
                ['fileUpload'], 'file', 'skipOnEmpty' => false, 'extensions' => Yii::$app->params['media.extensions'], 'maxSize' => Yii::$app->params['media.maxSize'],
            ],
        ];
    }

    public function getInputName()
    {
        return 'fileUpload';
    }

}