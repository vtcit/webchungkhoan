<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%category_meta}}".
 *
 * @property int $cat_id
 * @property string $key
 * @property string $value
 *
 * @property Category $cat
 */
class CategoryMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category_meta}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_id', 'key'], 'required'],
            [['cat_id'], 'integer'],
            [['value'], 'safe'],
            [['key'], 'string', 'max' => 20],
            [['cat_id', 'key'], 'unique', 'targetAttribute' => ['cat_id', 'key']],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['cat_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => Yii::t('app', 'Category'),
            'key' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * Gets query for [[Cat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Category::className(), ['id' => 'cat_id']);
    }
}
