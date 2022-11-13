<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\models\User;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $mime_type
 * @property string $extension
 * @property int $width
 * @property int $height
 * @property string $file
 * @property int $filesize
 * @property string $sizes
 * @property string $image_meta
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Media extends \yii\db\ActiveRecord
{
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%media}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'width', 'height', 'filesize', 'created_at', 'updated_at'], 'integer'],
            [['mime_type', 'width', 'height', 'file', 'filesize', 'title'], 'required'],
            [['sizes', 'image_meta', 'description'], 'string'],
            [['mime_type'], 'string', 'max' => 100],
            [['extension'], 'string', 'max' => 50],
            [['file', 'title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'mime_type' => Yii::t('app', 'Mime Type'),
            'extension' => Yii::t('app', 'Extension'),
            'width' => Yii::t('app', 'Width'),
            'height' => Yii::t('app', 'Height'),
            'file' => Yii::t('app', 'File'),
            'filesize' => Yii::t('app', 'Filesize'),
            'sizes' => Yii::t('app', 'Sizes'),
            'image_meta' => Yii::t('app', 'Image Meta'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[PostMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostMedia()
    {
        return $this->hasMany(PostMedia::className(), ['media_id' => 'id']);
    }

    /**
     * Gets query for [[PostMedia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPageMedia()
    {
        return $this->hasMany(PageMedia::className(), ['media_id' => 'id']);
    }
}
