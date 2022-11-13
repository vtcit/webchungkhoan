<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\Plan;
use common\models\PlanUser;
use yii\filters\VerbFilter;
use common\models\LoginForm;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use frontend\models\PasswordForm;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;

class UserController extends \frontend\components\FrontController
{
    public function behaviors()
    {
        return [ ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // $this->layout = 'base';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $user->updateRole('member');
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword() {
        $this->layout = 'dashboard';
        if (Yii::$app->user->isGuest) {
            // return $this->goHome();
			Yii::$app->session->setFlash('error', Yii::t('app', 'Please login first to continue, or create an account.'));
			return $this->redirect(['user/login']);
        }


        $model = new PasswordForm;
        $modelUser = Yii::$app->user->identity;
     
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            try{
                $modelUser->setPassword($model->new_password);
                $modelUser->generateAuthKey();
                if($modelUser->save()) {
                    Yii::$app->getSession()->setFlash( 'success','Password changed' );
                }else{
                    Yii::$app->getSession()->setFlash( 'error','Password not changed' );
                }
                return $this->redirect(['index']);
            }catch(Exception $e) {
                Yii::$app->getSession()->setFlash( 'error',"{$e->getMessage()}" );
            }
        }
        return $this->render('changepassword',[
            'model'=>$model
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTransaction()
    {
        $this->layout = 'dashboard';
        if (Yii::$app->user->isGuest) {
            // return $this->goHome();
			\Yii::$app->session->setFlash('error', \Yii::t('app', 'Please login first to continue, or create an account.'));
			return $this->redirect(['user/login']);
        }
        $model = Yii::$app->user->identity;
        return $this->render('transaction', [
            'model' => $model,
        ]);
    }
}
