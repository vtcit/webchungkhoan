<?php

namespace backend\controllers;

use backend\components\BackendController;
use Yii;
use common\models\Media;
use common\models\UploadForm;
use common\models\MediaSearch;
use common\helpers\VusInflector;
use common\helpers\MediaHelper;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\Url;


/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends BackendController
{
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
        ];
    }

    /**
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex($type = '')
    {
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(!in_array($type, ['grid', 'list', 'modal']))
        {
            $type = 'list';
        }
        if('modal' == $type)
        {
            $type ='grid';
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'type' => $type,
            ]);
        }
        // Default type
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single Media model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id = [])
    {
        $selection = Yii::$app->request->post('id');
        if($selection && is_array($selection))
        {
            $id = $selection;
        }
        if(!is_array($id))
        {
            $id = [$id];
        }

        $files = [];
        foreach($id as $_id)
        {
            $model = $this->findModel($_id);

            $files[] = $model->title;
            MediaHelper::unlink($model->file);

            $sizes = Yii::$app->params['media.thumbs'];
            if($model->sizes)
            {
                foreach(json_decode($model->sizes) as $k=>$thumb)
                {
                    MediaHelper::unlink($thumb->file);
                }
            }

            $model->delete();
        }

        if(Yii::$app->request->isAjax)
        {
            //Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->asJson(['success' => true, 'id' => $id]);
        }

        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Successfully deleted file(s): {files}', ['files' => '<code>'.implode('</code> <code>', $files).'</code>']));

        return $this->redirect(['index']);
    }

    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpload($type = '')
    {
        $model = new UploadForm();

        $isAjax = ('ajax' == $type || Yii::$app->request->isAjax);
        $return = [];
        $error = '';

        if (Yii::$app->request->isPost)
        {
            if($isAjax)
            {
                $model->fileUpload = UploadedFile::getInstanceByName('file');
            }
            else
            {
                $model->fileUpload = UploadedFile::getInstance($model, 'fileUpload');
            }

            if($model->validate())
            {
                $fileUpload = MediaHelper::upload($model->fileUpload);
                
                if($fileUpload['error'])
                {
                    $error = $fileUpload['message'];
                    Yii::$app->getSession()->setFlash('error', $fileUpload['message']);
                }
                else
                {
                    //Create Thumbnails
                    $thumbCfg = Yii::$app->params['media.thumbs'];
                    $thumbs = MediaHelper::createThumbs($fileUpload['file'], $thumbCfg);

                    $modelMedia = new Media([
                        //'user_id' => '',
                        //'' => '',
                        'title' => $fileUpload['title'],
                        //'description' => '',
                        'file' => $fileUpload['file'],
                        'width' => $fileUpload['width'],
                        'height' => $fileUpload['height'],
                        'mime_type' => $fileUpload['mime_type'],
                        'extension' => $fileUpload['extension'],
                        'filesize' => $fileUpload['filesize'],
                        'sizes' => json_encode($thumbs),
                        //'image_meta' => '',
                    ]);
                    if($modelMedia->validate() && $modelMedia->save())
                    {
                        // OK!
                        if(!$isAjax)
                        {
                            $this->redirect(['index']);
                            return false;
                        }

                        $return['location'] =  Yii::$app->urlManagerFrontend->createUrl(Yii::getAlias('@uploaddir').'/'.$thumbs['thumbnail']['file']);
                        $return['id'] =  $modelMedia->id;
                        $return['url'] =  Url::to(['media/update', 'id' => $modelMedia->id]);
                        $return['title'] =  $modelMedia->title;
                        $return['filesize'] =  $modelMedia->filesize;
                        $return['mime_type'] =  $modelMedia->mime_type;
                    }
                    else  /* $modelMedia->validate()->save() */
                    {
                        $error = Yii::t('app', 'Fail upload file {0}', [$fileUpload['title']]);
                        // remove file and thumbs uploaded
                        $files = [$fileUpload['file']];
                        $files = array_unique(array_merge($files, array_column($thumbs, 'file')));
                        foreach($files as $f)
                        {
                            MediaHelper::unlink($f);
                        }
                    }
                } //if else($fileUpload['error'])
            } //if($model->validate())
            else
            {
                $error = implode(', ', array_values($model->errors['fileUpload']));
            }
        } // if (Yii::$app->request->isPost)

        if($isAjax)
        {
            //Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            if($error)
                $return['error'] = $error;

            return $this->asJson($return);
        }

        return $this->render('upload', [
            'model' => $model,
        ]);
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
