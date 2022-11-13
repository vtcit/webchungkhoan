<?php
namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class CategoryBuildTree extends Widget
{
    public $categories = [];
    public $nameAttribute = 'title';
    public $idAttribute = 'id';
    public $format = 'object';
    public $format = 'object';
    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return Html::encode($this->html);
    }
}
