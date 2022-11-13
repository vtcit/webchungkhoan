<?php

namespace frontend\controllers;

use Yii;
use common\models\Stock;
use common\models\PlanUser;
use yii\filters\AccessControl;
use common\models\Recommendation;
use yii\web\ForbiddenHttpException;
use common\models\RecommendationDaily;
use frontend\components\FrontController;

class RecommendationController extends FrontController
{
    public $layout = 'dashboard';
    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['member', 'memberTrial'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['member'],
                    ],
                ], //rules
            ], // access
        ];
    }
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            // return $this->goHome();
			Yii::$app->session->setFlash('error', Yii::t('app', 'Please login first to continue, or create an account.'));
			$this->redirect(['user/login']);
            return false;
        }

        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
        $imUser = Yii::$app->user->identity;
        $planUserModel = PlanUser::find()
            ->where(['status' => 1, 'user_id' => $imUser->id])
            ->andWhere(['>', 'end_at', time()])
            ->all()
            ;
        if(!$planUserModel) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.')); 
            return false;
        }

        if (!parent::beforeAction($action)) {
            return false;
        }
    
        // other custom code here
    
        return true; // or false to not run the action
    }

    public function actionIndex()
    {
        $model = RecommendationDaily::find()
            ->where(['created_date' => date('Y-m-d', time())])
            ->orderBy(['id' => SORT_DESC])
            ->one()
            ;
        return $this->render('index', ['model' => $model]);
    }

    public function actionHistory($code = '', $date_start = '', $date_end = '')
    {
        $fields = [
            'stock_code' => Yii::t('app', 'Mã CK'),
            'created_at' => Yii::t('app', 'Thời gian'),
            'tin_hieu' => Yii::t('app', 'Tín hiệu'),
            'gia_khuyen_nghi' => Yii::t('app', 'Giá khuyến nghị'),
            'ty_trong' => Yii::t('app', 'Tỷ trọng'),
        ];
        $models = null;
        $date_start = $date_start ? : date('d-m-Y', strtotime('- 7 day'));
        $date_end = $date_end ? : date('d-m-Y', time());
        if($code && $date_start && $date_start) {
            $models = Recommendation::find()
                ->select(implode(', ', array_keys($fields)))
                ->where(['stock_code' => $code])
                ->andWhere(['between', 'created_date',  date('Y-m-d', strtotime($date_start)), date('Y-m-d', strtotime($date_end))])
                ->all();
        }
        $columns = Stock::find()->select('code')->column();
        $stock_codes = [];
        foreach($columns as $v) {
            $stock_codes[$v] = $v;
        }
        return $this->render('history', [
            'stock_codes' => $stock_codes,
            'code' => $code,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'models' => $models,
            'fields' => $fields,
        ]);
    }
}
