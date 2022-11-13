<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%page_meta}}".
 *
 * @property int $page_id
 * @property string $key
 * @property string $value
 *
 * @property Post $post
 */
class PageMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_meta}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'key'], 'required'],
            [['page_id'], 'integer'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 20],
            [['page_id', 'key'], 'unique', 'targetAttribute' => ['page_id', 'key']],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('app', 'Page'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * Gets query for [[Page]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
