<?php

namespace backend\controllers;

use Yii;
use common\models\Plan;
use common\models\User;
use common\models\PlanUser;
use yii\helpers\ArrayHelper;
use common\models\UserSearch;
use yii\filters\AccessControl;
use backend\models\PlanAddForm;
use yii\web\NotFoundHttpException;
use backend\components\BackendController;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BackendController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [ ];
    }

    // public function beforeAction($action)
    // {
    //     if(!Yii::$app->user->isGuest && !Yii::$app->user->can('accessAdmin')) {
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'Bạn không có quyền truy cập khu vực Admin.'));
    //         Yii::$app->user->logout();
    //         return $this->goHome();
    //     }
    //     return true;
    // }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTransaction($id)
    {
        $this->layout = 'user-panel';
        $model = $this->findModel($id);
        $modelPlanUser = new PlanUser();

        if ($this->request->isPost) {
            if ($modelPlanUser->load($this->request->post())) {
                $plan = Plan::findOne($modelPlanUser->plan_id);
                $modelPlanUser->during_time = $plan->during_time;
                // $modelPlanUser->created_at = time();
                $modelPlanUser->save();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Thêm gói dịch vụ thành công!'));
                return $this->refresh();
            }
        } else {
            foreach($modelPlanUser->errors as $field =>  $error) {
                Yii::$app->session->setFlash('error', $error);
            }
            $modelPlanUser->loadDefaultValues();
        }
        $availablePlans = ArrayHelper::map(Plan::find()->where(['status' => 1])->select('id, name')->limit(20)->all(), 'id', 'name');
        return $this->render('transaction', [
            'model' => $model,
            'availablePlans' => $availablePlans,
            'modelPlanUser' => $modelPlanUser,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'user-panel';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->layout = 'user-panel';
        $model = $this->findModel($id);
        $model->role = ArrayHelper::getColumn($model->roles, 'name');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {        
            try{
                if($model->new_password !== null && $model->new_password !== '') {
                    $model->setPassword($model->new_password);
                    $model->generateAuthKey();
                }
                $model->save();
            }catch(Exception $e) {
                Yii::$app->getSession()->setFlash( 'error',"{$e->getMessage()}" );
            }
            $model->updateRole($model->role);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Save successfully!'));
            return $this->redirect(['update', 'id' => $model->id]);
        }
        else
        {
            foreach($model->errors as $field =>  $error)
            {
                Yii::$app->session->setFlash('error', $error);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
