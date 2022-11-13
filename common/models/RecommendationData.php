<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "xz9i2_stock_recommendation_daily_data".
 *
 * @property int $stock_re_daily_id
 * @property string $stock_code
 * @property string $tin_hieu
 * @property string $created_date
 * @property int $created_at
 * @property float $gia_khuyen_nghi
 * @property float $gia_hien_tai
 * @property int $klgd
 * @property int $ty_trong
 * @property string|null $note
 *
 * @property Stock $stockCode
 * @property StockRecommendationDaily $stockReDaily
 */
class RecommendationData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%stock_recommendation_daily_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_re_daily_id', 'stock_code', 'tin_hieu', 'created_date', 'created_at', 'gia_khuyen_nghi', 'gia_hien_tai', 'klgd', 'ty_trong'], 'required'],
            [['stock_re_daily_id', 'created_at', 'klgd', 'ty_trong'], 'integer'],
            [['tin_hieu', 'note'], 'string'],
            [['created_date'], 'safe'],
            [['gia_khuyen_nghi', 'gia_hien_tai'], 'number'],
            [['stock_code'], 'string', 'max' => 20],
            [['stock_re_daily_id', 'stock_code'], 'unique', 'targetAttribute' => ['stock_re_daily_id', 'stock_code']],
            [['stock_re_daily_id'], 'exist', 'skipOnError' => true, 'targetClass' => StockRecommendationDaily::class, 'targetAttribute' => ['stock_re_daily_id' => 'id']],
            [['stock_code'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_code' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'stock_re_daily_id' => Yii::t('app', 'Stock Re Daily ID'),
            'stock_code' => Yii::t('app', 'Stock Code'),
            'tin_hieu' => Yii::t('app', 'Tin Hieu'),
            'created_date' => Yii::t('app', 'Created Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'gia_khuyen_nghi' => Yii::t('app', 'Gia Khuyen Nghi'),
            'gia_hien_tai' => Yii::t('app', 'Gia Hien Tai'),
            'klgd' => Yii::t('app', 'Klgd'),
            'ty_trong' => Yii::t('app', 'Ty Trong'),
            'note' => Yii::t('app', 'Note'),
        ];
    }

    /**
     * Gets query for [[StockCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockCode()
    {
        return $this->hasOne(Stock::class, ['code' => 'stock_code']);
    }

    /**
     * Gets query for [[StockReDaily]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockReDaily()
    {
        return $this->hasOne(StockRecommendationDaily::class, ['id' => 'stock_re_daily_id']);
    }
}
