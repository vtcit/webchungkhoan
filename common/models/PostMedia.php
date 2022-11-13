<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%post_media}}".
 *
 * @property string $post_id
 * @property string $media_id
 * @property string $type
 * @property int $is_featured
 * @property int $order
 *
 * @property Post $post
 * @property Media $media
 */
class PostMedia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_media}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'media_id', 'type'], 'required'],
            [['post_id', 'media_id', 'is_featured', 'order'], 'integer'],
            [['type'], 'string', 'max' => 150],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['media_id'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'post_id' => Yii::t('app', 'Post ID'),
            'media_id' => Yii::t('app', 'Media ID'),
            'type' => Yii::t('app', 'Type'),
            'is_featured' => Yii::t('app', 'Is Featured'),
            'order' => Yii::t('app', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }
}
