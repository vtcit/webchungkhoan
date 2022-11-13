<?php

namespace backend\controllers;

use backend\components\BackendController;
use Yii;
use yii\base\Model;
use common\models\Post;
use common\models\Category;
use common\models\PostMeta;
use common\models\PostSearch;
use common\models\PostMedia;
use common\models\PostCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii\helpers\ArrayHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BackendController
{
    protected $type;

    public function init()
    {
        parent::init();
        $this->type = 'post';
        $this->view->params['type'] = (object) Post::getTypes()[$this->type];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex($type = 'post')
    {
        $this->loadType($type); //Yii::$app->request->get('type')
        $searchModel = new PostSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'availableCats' => $this->getAvailableCats(),
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->loadType(Yii::$app->request->get('type'));
        $model = new Post(['type' => $this->type, 'status' => 1]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->savePostCategory($model);
            $this->savePostMedia($model);
            $this->savePostMeta($model);
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
            'availableCats' => $this->getAvailableCats(),
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->loadType($model->type);
        $model->category_ids = ArrayHelper::getColumn($model->postCategories, 'cat_id');
        $model->media_ids = ArrayHelper::getColumn($model->postMedia, 'media_id');
        $model->meta = ArrayHelper::map($model->postMetas, 'key', 'value');

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->savePostCategory($model);
            $this->savePostMedia($model);
            $this->savePostMeta($model);
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
            'availableCats' => $this->getAvailableCats(),
        ]);
    }

    protected function getAvailableCats()
    {
        static $availableCats;
        $availableCats = [];
        if(isset($this->view->params['type']->category))
        {
            foreach($this->view->params['type']->category as $catType)
            {
                if(isset(Category::getTypes()[$catType]))
                {
                    $catType = (object) Category::getTypes()[$catType];
                    $catType->items = ArrayHelper::map((new Category())->getAvailableCats()->where(['type' => $catType->name])->all(), 'id', 'title');
                    if(!$catType->items) continue;
                    $availableCats[$catType->name] = $catType;
                }
            }
        }
        return $availableCats;
    }

    protected function savePostCategory($model)
    {
        PostCategory::deleteAll(['post_id' => $model->id]);
        if($model->category_ids)
        {
            foreach($model->category_ids as $category_id)
            {
                $modelPostCategory = new PostCategory([
                    'cat_id' => $category_id,
                    'post_id' => $model->id,
                ]);
                if(!$modelPostCategory->save())
                {
                    foreach($modelPostCategory->errors as $field =>  $error)
                    {
                        Yii::$app->session->setFlash('error', $error);
                    }
                }
            }
        }
    }

    protected function savePostMedia($model)
    {
        PostMedia::deleteAll(['post_id' => $model->id]);
        if($model->media_ids)
        {
            $k = 0;
            foreach($model->media_ids as $media_id)
            {
                $media_id = intval($media_id);
                if(!$media_id) continue;
                $modelPostMedia = new PostMedia([
                    'media_id' => $media_id,
                    'post_id' => $model->id,
                    'type' => 'images',
                    'is_featured' => ($k? null:1),
                    'order' => $k,
                ]);
                if(!$modelPostMedia->save())
                {
                    foreach($modelPostMedia->errors as $field =>  $error)
                    {
                        Yii::$app->session->setFlash('error', $error);
                    }
                }
                $k++;
            }
        }
    }

    protected function savePostMeta($model)
    {
        PostMeta::deleteAll(['post_id' => $model->id]);
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

                $modelPostMeta = new PostMeta([
                    'post_id' => $model->id,
                    'key' => $key,
                    'value' => $value,
                ]);

                if(!$modelPostMeta->save())
                {
                    foreach($modelPostMeta->errors as $error)
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
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Load Post type, and provide the view
     * @return Mixed
     */
    protected function loadType($type = '') {
        if(!$type || $type == $this->type) return;

        if(in_array($type, array_keys(Post::getTypes()))) {
            $this->type = $type;
            $this->view->params['type'] = (object) Post::getTypes()[$this->type];
        }
    }
}
