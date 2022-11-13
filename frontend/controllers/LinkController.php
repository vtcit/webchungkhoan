<?php

namespace frontend\controllers;

use Yii;
// use yii\web\Controller;
use frontend\components\FrontController;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class LinkController extends FrontController
{

    public function actionUrl($code) {
        $url = base64_decode($code);
        $this->checkUrlExists($url);
        return file_get_contents($url);
    }

    public function actionImage($code) {
        $url = base64_decode($code);
        $this->checkUrlExists($url);
        // $imginfo = getimagesize($url);
        // header("Content-type: {$imginfo['mime']}");
        // return readfile($url);
        $this->redirect($url);
    }

    protected function checkUrlExists($url) {
        if (!$fp = curl_init($url))
            throw new NotFoundHttpException(Yii::t('app', 'The Url does not exist.'));

        return true;
    }
}