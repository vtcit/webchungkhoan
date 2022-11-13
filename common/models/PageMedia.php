<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%post_media}}".
 *
 * @property string $page_id
 * @property string $media_id
 * @property string $type
 * @property int $is_featured
 * @property int $order
 *
 * @property Post $post
 * @property Media $media
 */
class PageMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_media}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'media_id', 'type'], 'required'],
            [['page_id', 'media_id', 'is_featured', 'order'], 'integer'],
            [['type'], 'string', 'max' => 150],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'page_id' => Yii::t('app', 'Page'),
            'media_id' => Yii::t('app', 'Media'),
            'type' => Yii::t('app', 'Type'),
            'is_featured' => Yii::t('app', 'Is Featured'),
            'order' => Yii::t('app', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}
