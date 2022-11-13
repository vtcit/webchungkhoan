<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "xz9i2_stock_recommendation_daily".
 *
 * @property int $id
 * @property string $created_date
 * @property int $created_at
 * @property string|null $data_stock
 * @property string|null $desc
 *
 * @property Stock[] $stockCodes
 * @property StockRecommendationDailyData[] $stockRecommendationDailyDatas
 */
class RecommendationDaily extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stock_recommendation_daily}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_date', 'created_at'], 'required'],
            [['created_date'], 'safe'],
            [['created_at'], 'integer'],
            [['data_stock', 'desc'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_date' => Yii::t('app', 'Created Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'data_stock' => Yii::t('app', 'Data Stock'),
            'desc' => Yii::t('app', 'Desc'),
        ];
    }

    /**
     * Gets query for [[StockCodes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockCodes()
    {
        return $this->hasMany(Stock::class, ['code' => 'stock_code'])->viaTable('xz9i2_stock_recommendation_daily_data', ['stock_re_daily_id' => 'id']);
    }

    /**
     * Gets query for [[StockRecommendationDailyDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockRecommendationDailyDatas()
    {
        return $this->hasMany(StockRecommendationDailyData::class, ['stock_re_daily_id' => 'id']);
    }
}
