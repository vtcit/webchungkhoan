<?php

namespace backend\controllers;

use backend\components\BackendController;
use Yii;
use common\models\Page;
use common\models\PageSearch;
use common\models\PageMeta;
use common\models\PageMedia;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends BackendController
{
    protected $type;

    public function init()
    {
        parent::init();
        $this->type = 'page';
        $this->view->params['type'] = (object) Page::getTypes()[$this->type];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->loadType(Yii::$app->request->get('type'));
        $searchModel = new PageSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->loadType(Yii::$app->request->get('type'));
        $model = new Page(['type' => $this->type]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->savePageMedia($model);
            $this->savePageMeta($model);
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

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->loadType($model->type);

        $model->media_ids = ArrayHelper::getColumn($model->pageMedia, 'media_id');
        $model->meta = ArrayHelper::map($model->pageMetas, 'key', 'value');

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->savePageMedia($model);
            $this->savePageMeta($model);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Save successfully!'));
            return $this->redirect(['update', 'id' => $model->id]);
        }
        // else
        // {
            // foreach($model->errors as $field => $error)
            // {
            //     // echo "{$field} => {$error} <br>";
            //     Yii::$app->session->setFlash('error', $error);
            // }
        // }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function savePageMedia($model)
    {
        PageMedia::deleteAll(['page_id' => $model->id]);
        if($model->media_ids)
        {
            foreach($model->media_ids as $k=>$media_id)
            {
                $modelPageMedia = new PageMedia([
                    'media_id' => $media_id,
                    'page_id' => $model->id,
                    'type' => 'images',
                    'is_featured' => ($k? null:1),
                    'order' => $k,
                ]);
                if(!$modelPageMedia->save())
                {
                    foreach($modelPageMedia->errors as $field => $error)
                    {
                        Yii::$app->session->setFlash('error', $error);
                    }
                }
            }
        }
    }

    protected function savePageMeta($model)
    {
        PageMeta::deleteAll(['page_id' => $model->id]);
        if($model->meta)
        {
            foreach($model->meta as $key => $value)
            {
                if(is_array($value) && $value)
                {
                    $value = array_unique(array_filter($value));
                    if(!$value)
                        continue;

                    $value = json_encode($value);
                }

                if(!$value) continue;

                $modelPageMeta = new PageMeta([
                    'page_id' => $model->id,
                    'key' => $key,
                    'value' => $value,
                ]);

                if(!$modelPageMeta->save())
                {
                    foreach($modelPageMeta->errors as $error)
                    {
                        Yii::$app->session->setFlash('error', $error);
                    }
                }
            }
        }
    }

    /**
     * Deletes an existing Post model.
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
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Load page type, and provide the view
     * @return Mixed
     */
    protected function loadType($type = '')
    {
        if(!$type || $type == $this->type) return;

        if(in_array($type, array_keys(Page::getTypes())))
        {
            $this->type = $type;
            $this->view->params['type'] = (object) Page::getTypes()[$this->type];
        }
    }
}
