<?php

namespace backend\controllers;

use backend\components\BackendController;
use Yii;
use common\models\Media;
use common\models\UploadForm;
use common\models\MediaSearch;
use common\helpers\VusInflector;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Url;

use yii\imagine\Image;
//use Imagine\Gd;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
//use Imagine\Image\BoxInterface;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class UploadController extends BackendController
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'upload')
        {
            //$this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    //'upload' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpload($partial=false)
    {
        $model = new UploadForm();

        $isJson = Yii::$app->request->get('json');
        $isTinymce = Yii::$app->request->get('tinymce');
        if($isTinymce)
        {
            $isJson = true;
        }
        if($isJson)
        {
            $partial = true;
        }

        if (Yii::$app->request->isPost) {
            if($isTinymce)
            {
                $model->fileUpload = UploadedFile::getInstanceByName('file');
            }
            else
            {
                $model->fileUpload = UploadedFile::getInstance($model, 'fileUpload');
            }
            //print_r($model->fileUpload);exit;
            if($model->validate())
            {
                foreach ($model->fileUpload as $fileUpload)
                {
                    // upload file
                }
                $kinc = 0;
                do {
                    $baseName = date('Y/m').'/'.VusInflector::slug($model->fileUpload->baseName).($kinc? "-$kinc" : '');
                    $file =  $baseName.'.'.$model->fileUpload->extension; 
                    $saveFile =  FileHelper::normalizePath(Yii::getAlias('@upload/').$file);
                    $kinc++;
                } while (file_exists($saveFile));

                $createdDir = FileHelper::createDirectory(dirname($saveFile));

                $model->fileUpload->saveAs($saveFile);

                if($model->fileUpload->hasError)
                {
                    Yii::$app->getSession()->setFlash('error', $model->message);
                }
                else
                {
                    /* Crop & Thumbnail */
                    $imgImagine = Image::getImagine()->open($saveFile);

                    $size = $imgImagine->getSize();
                    list($w, $h) = [$size->getWidth(), $size->getHeight()];
                    $thumbs = Yii::$app->params['media.thumbs'];
                    $sizes = [];
                    $ratio = $w/$h;
                    foreach($thumbs as $k=>$thumb)
                    {
                        $keepAspectRatio = !$thumb['crop'];
                        list($_w, $_h) = self::dimensions($thumb['width'], $thumb['height'], $ratio, $keepAspectRatio);
                        $_file =  $baseName.'_'.intval($_w).'x'.intval($_h).'.'.$model->fileUpload->extension; 
                        $_saveFile =  FileHelper::normalizePath(Yii::getAlias('@upload/').$_file);
                        if($keepAspectRatio)
                            $imgImagine->thumbnail(new Box($_w, $_h))->save($_saveFile, ['quality' => 90]); // default: THUMBNAIL_INSET
                        else
                            $imgImagine->thumbnail(new Box($_w, $_h), ImageInterface::THUMBNAIL_OUTBOUND)->save($_saveFile, ['quality' => 90]);


                        $sizes[$k] = [
                            'file' => $_file,
                            'width' => $_w,
                            'height' => $_h,
                            'mime_type' => $model->fileUpload->type,
                        ];
                    }

                    $modelMedia = new Media([
                        //'user_id' => '',
                        //'' => '',
                        'title' => $model->fileUpload->baseName.($kinc>1? ' ('.$kinc.')' : ''),
                        //'description' => '',
                        'file' => $file,
                        'width' => $w,
                        'height' => $h,
                        'mime_type' => $model->fileUpload->type,
                        'extension' => $model->fileUpload->extension,
                        'filesize' => $model->fileUpload->size,
                        'sizes' => json_encode($sizes),
                        //'image_meta' => '',
                    ]);
                    if ($modelMedia->validate() && $modelMedia->save()) {
                        // OK!
                    }
                }
                if($isJson)
                {
                    Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                    $return = [
                        'location' => Url::to('@uploadurl/'.$file, true),
                    ];
                    return $return;
                }
                else
                {
                    $this->redirect(['index']);
                }

                return;
            }
        }

        if($partial)
            return $this->renderPartial('upload', [
                'model' => $model,
            ]);
        else
            return $this->render('upload', [
                'model' => $model,
            ]);
    }

    protected static function dimensions($width, $height, $ratio = 4/3, $keepAspectRatio = true)
    {
        if ($keepAspectRatio === false) {
            if ($height === null) {
                $height = ceil($width / $ratio);
            } elseif ($width === null) {
                $width = ceil($height * $ratio);
            }
        } else {
            if ($height === null) {
                $height = ceil($width / $ratio);
            } elseif ($width === null) {
                $width = ceil($height * $ratio);
            } elseif ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
        }
        return [$width, $height];
    }
    protected static function unlinkMedia($file)
    {
        $file = FileHelper::normalizePath(Yii::getAlias('@upload/').$file);
        if(file_exists($file))
            return FileHelper::unlink($file);

        return false;
    }
    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
