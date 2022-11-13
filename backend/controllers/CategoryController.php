<?php

namespace backend\controllers;

use backend\components\BackendController;
use Yii;
use yii\base\Model;
use common\models\Category;
use common\models\CategoryMeta;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BackendController
{
    protected $type;
    private static $catTree = array();

    public function init()
    {
        parent::init();
        /* Default */
        $this->type = 'category';
        $this->view->params['type'] = (object) Category::getTypes()[$this->type];
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
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if(!Yii::$app->user->can('managerPost')) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Bạn không có quyền truy cập trang này'));
            $this->goHome();
        }
        return true;
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->loadType(Yii::$app->request->get('type'));

        $dataProvider = new ActiveDataProvider([
            'query' => Category::find()->where(['type' => $this->type]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->loadType(Yii::$app->request->get('type'));
        $model = new Category(['type' => $this->type]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveCategoryMeta($model);
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
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->loadType($model->type);
        $model->meta = ArrayHelper::map($model->categoryMetas, 'key', 'value');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveCategoryMeta($model);
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

    protected function saveCategoryMeta($model)
    {
        CategoryMeta::deleteAll(['cat_id' => $model->id]);
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

                $modelCategoryMeta = new CategoryMeta([
                    'cat_id' => $model->id,
                    'key' => $key,
                    'value' => $value,
                ]);

                if(!$modelCategoryMeta->save())
                {
                    foreach($modelCategoryMeta->errors as $error)
                    {
                        Yii::$app->session->setFlash('error', $error);
                    }
                }
            }
        }
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $type = $model->type;
        $model->delete();

        return $this->redirect(['index', 'type' => $type]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Load type Category, and provide the view
     * @return Mixed
     */
    protected function loadType($type = '')
    {
        if(!$type || $type == $this->type) return;

        if(in_array($type, array_keys(Category::getTypes())))
        {
            $this->type = $type;
            $this->view->params['type'] = (object) Category::getTypes()[$this->type];
        }
    }
}
