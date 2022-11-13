<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%stock}}".
 *
 * @property string $code
 * @property string $name
 * @property string $exchange
 * @property int $status
 *
 * @property StockRecommendation $stockRecommendation
 * @property StockRecommendationDay $stockRecommendationDay
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stock}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['status'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name', 'exchange'], 'string', 'max' => 250],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'exchange' => Yii::t('app', 'Exchange'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[StockRecommendation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockRecommendation()
    {
        return $this->hasOne(StockRecommendation::className(), ['stock_code' => 'code']);
    }

    /**
     * Gets query for [[StockRecommendationDay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockRecommendationDay()
    {
        return $this->hasOne(StockRecommendationDay::className(), ['stock_code' => 'code']);
    }
}
