<?php


namespace backend\components;

use Yii;
//use yii\base\Component;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii\helpers\ArrayHelper;

use yii\filters\AccessControl;

class BackendController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                // 'only' => ['login', 'logout', 'signup', 'index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['editor'],
                    ],
                ], //rules
            ], // access
        ];
    }

    public function beforeAction1($action)
    {
        if(!Yii::$app->user->isGuest && !Yii::$app->user->can('accessAdmin')) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Bạn không có quyền truy cập khu vực Admin.'));
            Yii::$app->user->logout();
            return $this->goHome();
        }
        return true;
    }
}
