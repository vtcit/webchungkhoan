<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;
use common\models\Category;
use common\models\PostSearch;
use yii\data\ActiveDataProvider;
// use yii\web\Controller;
use frontend\components\FrontController;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends FrontController
{
    protected $type;

    public function init()
    {
        parent::init();
        $this->loadType('post');
    }
    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
        // return [];
    // }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex($type = 'post')
    {
        // if('post' == $type) {
        //     throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        // }

        $this->loadType($type);
        $searchModel = new PostSearch(['type' => $type]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->pagination->pageSize = 1;

        $view = 'index';
        $path = $this->viewPath. DIRECTORY_SEPARATOR .$view.'-'.$type.'.php';
        if (is_file($path)) {
            $view .= '-'.$type;
        }

        return $this->render($view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionCategory($slug, $type = 'category')
    {
        if (($category = Category::findOne(['slug' => $slug])) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $searchModel = new PostSearch(['status' => 1]);
        $catIds = [$category->id];
        $childIds = self::getCategoryTree($category->id, true /*$getOnlyIds*/);
        $catIds = ArrayHelper::merge($catIds, $childIds);
        $queryParams = ArrayHelper::merge(Yii::$app->request->queryParams,
            [
                'category' => $catIds,
            ]
        );

        $dataProvider = $searchModel->search($queryParams);
        // $dataProvider->pagination->pageSize = 1;

        $view = 'category';
        if (is_file($this->viewPath. DIRECTORY_SEPARATOR .$view.'-'.$slug.'.php')) {
            $view .= '-'.$slug;
        }
        elseif(is_file($this->viewPath. DIRECTORY_SEPARATOR .$view.'-'.$type.'.php')) {
            $view .= '-'.$type;
        }

        return $this->render($view, [
            'category' => $category,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $type, $slug)
    {
        $id = Yii::$app->Hashids->decode($id);
        $model = $this->findModel(['id' => $id, 'status' => 1]);

        if($type != $model->type || $slug != $model->slug) {
            $this->redirect($model->url);
        }

        $view = 'view';
        if (is_file($this->viewPath. DIRECTORY_SEPARATOR .$view.'-'.$model->slug.'.php')) {
            $view .= '-'.$model->slug;
        }
        elseif(is_file($this->viewPath. DIRECTORY_SEPARATOR .$view.'-'.$model->type.'.php')) {
            $view .= '-'.$model->type;
        }

        return $this->render($view, [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Load Post type, and provide the view
     * @return Mixed
     */
    protected function loadType($type = '')
    {
        if(!$type || $type == $this->type) return;

        $types = Post::getTypes();
        if(in_array($type, array_keys($types)))
        {
            $this->type = $type;
            $this->view->params['type'] = (object) $types[$this->type];
        }
    }

    /////////////////////////////

    private static $catTree = array();

    public static function getListTreeView($parent_id = null) {
            if (empty(self::$catTree)) {
                self::getCategoryTree($parent_id);
            }
            return self::visualTree(self::$catTree, 0);
     }
    public static function getCategoryTree($parent_id = null, $getOnlyIds = false) {
        if(!$parent_id) $parent_id = null;
        if (empty(self::$catTree)) {

            $rows = Category::find()->where(['parent_id' => $parent_id])->all();

            $ids = [];
            foreach ($rows as $item) {
                if($getOnlyIds)
                    $ids = ArrayHelper::merge($ids, self::getCatItems($item, $getOnlyIds));
                else
                    self::$catTree[] = self::getCatItems($item, $getOnlyIds);
            }
        }
        if($getOnlyIds)
        {
            return $ids;
        }
        return self::$catTree;
    }

    private static function getCatItems($model, $getOnlyIds = false, &$ids = []) {

        if (!$model)
            return;

        if (isset($model->categories)) {
            $chump = self::getCatItems($model->categories, $getOnlyIds, $ids);

            if($getOnlyIds)
            {
                $ids[] = $model->id;
                return $ids;
            }
            $res = ['id' => $model->id, 'label' => $model->title, 'ids' => $ids, 'items' => $chump];
            return $res;
        } else {
            if (is_array($model)) {
                $arr = [];
                foreach ($model as $leaves) {
                    $arr[] = self::getCatItems($leaves, $getOnlyIds, $ids);
                }
                return $arr;
            } else {

                if($getOnlyIds)
                {
                    $ids[] = $model->id;
                    return $ids;
                }
                return ['id' => $model->id, 'label' => $model->title, 'ids' => $ids, 'items' => null];
            }
        }
    }

    private static function visualTree($catTree, $level) {
        $res = array();
        foreach ($catTree as $item) {
            $res[$item['id']] = '' . str_pad('', $level * 2, '-') . ' ' . $item['label'];
            if (isset($item['items'])) {
                $res_iter = self::visualTree($item['items'], $level + 1);
                foreach ($res_iter as $key => $val) {
                    $res[$key] = $val;
                }
            }
        }
        return $res;
    }
}
