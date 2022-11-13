<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $birthday
 * @property string $email
 * @property string|null $tel
 * @property string|null $address
 * @property int|null $province_id
 *
 * @property Province $province
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    public $full_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'province_id'], 'integer'],
            [['full_name', 'email', 'tel'], 'required'],
            [['birthday'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 64],
            [['email', 'address', 'full_name'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 20],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Province::className(), 'targetAttribute' => ['province_id' => 'id']],
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
            'user_id' => Yii::t('app', 'User'),
            'full_name' => Yii::t('app', 'Full Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'birthday' => Yii::t('app', 'Birthday'),
            'email' => Yii::t('app', 'Email'),
            'tel' => Yii::t('app', 'Tel'),
            'address' => Yii::t('app', 'Address'),
            'province_id' => Yii::t('app', 'Province'),
        ];
    }

    public function beforeSave($insert)
    {
        if($this->full_name) {
            $full_name = explode(' ', $this->full_name);
            $this->setAttributes([
                'first_name' => array_pop($full_name),
                'last_name' => implode(' ', $full_name)
            ]);
        }

        if (!parent::beforeSave($insert)) {
            return false;
        }
        return true;
    }

    public function afterFind()
    {
        $this->full_name = $this->first_name;
        if($this->last_name) {
            $this->full_name = $this->last_name.' '.$this->first_name;
        }

        if (!parent::afterFind()) {
            return false;
        }
        return true;
    }

    /**
     * Gets query for [[Province]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Province::className(), ['id' => 'province_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getHashid()
    {
        return Yii::$app->Hashids->encode(intval($this->id));
    }
}
