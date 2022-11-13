<?php

namespace frontend\controllers;

use Yii;
use common\models\Page;
use yii\data\ActiveDataProvider;
// use yii\web\Controller;
use frontend\components\FrontController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends FrontController
{
    protected $type;

    public function init()
    {
        parent::init();
        $this->loadType('page');
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

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex($type = 'page')
    {
        if('page' == $type) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $this->loadType($type);
        $dataProvider = new ActiveDataProvider([
            'query' => Page::find()->where(['type' => $type, 'parent_id' => null]),
        ]);

        $view = 'index';
        $path = $this->viewPath. DIRECTORY_SEPARATOR .$view.'-'.$type.'.php';
        if (is_file($path)) {
            $view .= '-'.$type;
        }

        return $this->render($view, [
            'dataProvider' => $dataProvider,
            'type' => $type,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($slug, $type = 'page')
    {
        $model = $this->findModel(['slug' => $slug]);
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
     * Load Page type, and provide the view
     * @return Mixed
     */
    protected function loadType($type = '')
    {
        if(!$type || $type == $this->type) return;

        $types = Page::getTypes();
        if(in_array($type, array_keys($types)))
        {
            $this->type = $type;
            $this->view->params['type'] = (object) $types[$this->type];
        }
    }
}
