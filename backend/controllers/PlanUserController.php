<?php

namespace backend\controllers;

use Yii;
use common\models\PlanUser;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\components\BackendController;

class PlanUserController extends BackendController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'activate' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['admin'],
                    ],
                ], //rules
            ], // access
        ];
    }
    public function actionActivate($id, $user_id)
    {
        $model = $this->findModel($id, $user_id);
        if($model->status != 1) {
            $model->status = 1;
            $model->start_at = time();
            $model->end_at = strtotime('+'.intval($model->during_time).' days', $model->start_at);
            if($model->save()) {
                $userRoles = $model->user->getRoles();
                $can = false;
                foreach($userRoles as $role) {
                    if(in_array(key($role), ['admin', 'editor', 'member'])) {
                        $can = true;
                        break;
                    }
                }
                // Update Role to `member` if not ['admin', 'editor', 'member']!
                if(!$can) {
                    $model->user->updateRole('member');
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the PlanUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $plan_id, $user_id
     * @return PlanUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $user_id)
    {
        if (($model = PlanUser::findOne(['id' => $id, 'user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
