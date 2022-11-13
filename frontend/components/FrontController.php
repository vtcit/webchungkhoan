<?php

namespace frontend\components;

use yii\web\Controller;

/**
 * FrontController implements the CRUD actions for Page model.
 */
class FrontController extends Controller
{
    public function init()
    {
        parent::init();
        $this->view->params['metatag'] = [
	        'site_name' => 'Web chứng khoán',
	        'locale' => \Yii::$app->language,
        ];
        $this->view->params['css'] = [];
        $this->view->params['js'] = [];
    }
}
