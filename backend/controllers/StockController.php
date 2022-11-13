<?php

namespace backend\controllers;

use backend\components\BackendController;
use backend\models\StockImportForm;
use Yii;
use common\models\Stock;
use common\models\StockSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * StockController implements the CRUD actions for Stock model.
 */
class StockController extends BackendController
{
    public $layout = 'stock-panel';

    public function behaviors()
    {
        return  [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
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

    /**
     * Lists all Stock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stock model.
     * @param string $id
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
     * Creates a new Stock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Stock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImport()
    {
        $model = new StockImportForm();

        if (Yii::$app->request->isPost) {
            $model->fileCsv = UploadedFile::getInstance($model, 'fileCsv');
            if($model->validate()) {
                $baseName = 'csv/stock-code-'.microtime(true);
                $file =  $baseName.'.'.$model->fileCsv->extension; 
                $saveFile =  FileHelper::normalizePath(Yii::getAlias('@upload/').$file);
                FileHelper::createDirectory(dirname($saveFile));

                $model->fileCsv->saveAs($saveFile);

                if($model->fileCsv->hasError) {
                    Yii::$app->getSession()->setFlash('error', $model->message);
                } else {
                    if(($fileHandler = fopen($saveFile, 'r')) !== FALSE) {
                        // First line for fields name
                        $fields = [];
                        while(($line = fgetcsv($fileHandler, 1000, ",")) !== FALSE) {
                            if(empty($fields)) {
                                $fields = $line;
                                continue;
                            }
                            $data = [];
                            foreach($fields as $k =>$field) {
                                $data[$field] = trim($line[$k]);
                            }
                            $modelStock = new Stock();
                            $modelStock->load($data, '');
                            if($modelStock->validate() && $modelStock->save()) {
                                // OK!
                            }
                        }
                        // $connection->createCommand()->batchInsert('user', ['name', 'age'], [
                        //     ['Tom', 30],
                        //     ['Jane', 20],
                        //     ['Linda', 25],
                        // ])->execute();
                        fclose($fileHandler);
                    }
                    if(file_exists($saveFile)) {
                        FileHelper::unlink($saveFile);
                    }
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Imported successfull.'));
                    // $this->redirect(['index']);
                }
            }
        }

        return $this->render('import', [
            'model' => $model,
        ]);
    }

    public function actionExport($rows = 100) {
        if(!is_integer($rows) || $rows <= 0) {
            exit;
        }
        $stocks = Stock::find()->limit($rows)->all();
        $columns = array_keys((new Stock())->attributes);
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="stock-code-' . date('YmdHi') .'.csv"');
        echo implode(',', $columns)."\r\n";
        foreach($stocks as $stock) {
            $value = [];
            foreach($columns as $col) {
                $value[] = $stock->{$col};
            }
            echo implode(',', $value)."\r\n";
        }
         exit;
    }

    /**
     * Updates an existing Stock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Stock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Stock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Stock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
