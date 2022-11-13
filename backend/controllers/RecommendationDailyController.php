<?php

namespace backend\controllers;

use backend\components\BackendController;
use Yii;
use backend\models\StockImportForm;
use common\models\Recommendation;
use common\models\RecommendationDaily;
use common\models\RecommendationDailySearch;
use common\models\Stock;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * RecommendationDailyController implements the CRUD actions for RecommendationDaily model.
 */
class RecommendationDailyController extends BackendController
{
    public $layout = 'stock-panel';
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return  [
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
     * Lists all RecommendationDaily models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RecommendationDailySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Import a CSV file RecommendationDaily models.
     *
     * @return string
     */
    public function actionImport()
    {
        $model = new StockImportForm();

        if (Yii::$app->request->isPost) {
            $model->fileCsv = UploadedFile::getInstance($model, 'fileCsv');
            if($model->validate()) {
                $baseName = 'csv/recommendation-daily-'.microtime(true);
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
                        $data_stock = [];
                        $created_at = time();
                        $created_date = date('Y-m-d', $created_at);

                        while(($line = fgetcsv($fileHandler, 1000, ",")) !== FALSE) {
                            if(empty($fields)) {
                                $fields = $line;
                                continue;
                            }
                            $data = [];
                            foreach($fields as $k =>$field) {
                                if(!isset($line[$k])) continue;
                                if('MA_CK' == strtoupper($field)) {
                                    $field = 'stock_code';
                                }
                                $value = trim($line[$k]);
                                if($field == 'thoi_gian') {
                                    // $field = 'created_at';
                                    // $value = strtotime($value);
                                    // $data['created_date'] = date("Y-m-d", $value);
                                    continue;
                                }
                                $data[$field] = $value;
                            }
                            $data['created_date'] = $created_date;
                            $data['created_at'] = $created_at;
                            $data_stock[$data['stock_code']] = $data;
                        }
                        
                        // Import Recommendation Rows
                        if(!empty($data_stock)) {
                            /** Delete all old by date (current date) */
                            Recommendation::deleteAll(['created_date' => $created_date]);
                            /** Save new to record */
                            foreach($data_stock as $data) {
                                $modelRecommendation = new Recommendation();
                                $modelRecommendation->load($data, '');
                                if($modelRecommendation->validate() && $modelRecommendation->save()) {
                                    // OK!
                                }
                                else {
                                    foreach ($modelRecommendation->errors as $field =>  $error) {
                                        Yii::$app->session->setFlash('error', $error);
                                    }
                                }
                            }

                            // Import Recommendation Daily
                            $modelRecommendationDaily = new RecommendationDaily();
                            $modelRecommendationDaily->load([
                                'created_date' => $created_date,
                                'created_at' => $created_at,
                                'data_stock' => json_encode($data_stock),
                                'desc' => '',
                            ], ''); // !importal!  with empty string
                            if($modelRecommendationDaily->validate() && $modelRecommendationDaily->save()) {
                                // OK!
                            }
                            else {
                                foreach ($modelRecommendation->errors as $field =>  $error) {
                                    Yii::$app->session->setFlash('error', $error);
                                }
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
                    // return $this->redirect(['index']);
                    return $this->refresh();
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
        if($rows > 100) $row = 100;
        $columns = array_keys((new Recommendation())->attributes);
        foreach($columns as $k => $column) {
            // if($column == 'created_at') {
            //     unset($columns[$k]);
            // }
            // if($column == 'created_date') {
            //     $columns[$k] = 'thoi_gian';
            // }
            if(in_array($column, ['created_date', 'created_at'])) {
                unset($columns[$k]);
            }
        }
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="recommendation-daily-' . date('YmdHi') .'.csv"');
        echo implode(',', $columns)."\r\n";
        // $recommendationDaily = Recommendation::find()->limit($rows)->all();
        // foreach($recommendationDaily as $r) {
        //     $value = [];
        //     foreach($columns as $col) {
        //         $value[] = $r->{$col};
        //     }
        //     echo implode(',', $value)."\r\n";
        // }
         exit;
    }

    /**
     * Lists all RecommendationDaily models.
     *
     * @return string
     */
    public function actionHistory($code = '', $date_start = '', $date_end = '')
    {
        $fields = [
            'stock_code' => Yii::t('app', 'Mã CK'),
            'created_at' => Yii::t('app', 'Thời gian'),
            'tin_hieu' => Yii::t('app', 'Tín hiệu'),
            'gia_khuyen_nghi' => Yii::t('app', 'Giá khuyến nghị'),
            'ty_trong' => Yii::t('app', 'Tỷ trọng'),
        ];
        $models = null;
        $date_start = $date_start ? : date('d-m-Y', strtotime('- 7 day'));
        $date_end = $date_end ? : date('d-m-Y', time());
        if($code && $date_start && $date_start) {
            $models = Recommendation::find()
                ->select(implode(', ', array_keys($fields)))
                ->where(['stock_code' => $code])
                ->andWhere(['between', 'created_date',  date('Y-m-d', strtotime($date_start)), date('Y-m-d', strtotime($date_end))])
                ->all();
        }
        $columns = Stock::find()->select('code')->column();
        $stock_codes = [];
        foreach($columns as $v) {
            $stock_codes[$v] = $v;
        }
        return $this->render('history', [
            'stock_codes' => $stock_codes,
            'code' => $code,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'models' => $models,
            'fields' => $fields,
        ]);
    }

    /**
     * Displays a single RecommendationDaily model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing RecommendationDaily model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RecommendationDaily model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return RecommendationDaily the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecommendationDaily::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
