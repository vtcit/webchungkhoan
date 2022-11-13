<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use frontend\components\FrontController;

class DashboardController extends FrontController
{
    public $layout = 'dashboard';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['subscriber', 'memberTrial'],
                    ],
                ], //rules
            ], // access
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
