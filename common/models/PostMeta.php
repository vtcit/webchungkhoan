<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%post_meta}}".
 *
 * @property int $post_id
 * @property string $key
 * @property string $value
 *
 * @property Post $post
 */
class PostMeta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_meta}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'key'], 'required'],
            [['post_id'], 'integer'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 20],
            [['post_id', 'key'], 'unique', 'targetAttribute' => ['post_id', 'key']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'post_id' => Yii::t('app', 'Post ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
