<?php

namespace backend\models;

use Yii;
use yii\base\Model;

class StockImportForm extends Model
{
    /**
     * @var fileCsv
     */
    public $fileCsv;

    public function rules()
    {
        return [
            [
                ['fileCsv'], 'file', 'skipOnEmpty' => false, 'maxSize' => Yii::$app->params['media.maxSize'],
            ],
        ];
    }
    
    public function attributeLabels()
	{
		return [
			'fileCsv' => 'Select file .csv',
		];
	}

    public function getInputName()
    {
        return 'fileCsv';
    }

}