<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%plan_user}}".
 *
 * @property int $id
 * @property int $plan_id
 * @property int $user_id
 * @property int $during_time
 * @property int $created_at
 * @property int|null $start_at
 * @property int|null $end_at
 * @property int $status
 *
 * @property Plan $plan
 * @property User $user
 */
class PlanUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plan_user}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
                /* Save with [integer] timestamp
                 * Remove comment for save datetime format
                 */
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'user_id', 'during_time'], 'required'],
            [['plan_id', 'user_id', 'during_time', 'created_at', 'start_at', 'end_at', 'status'], 'integer'],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Plan::class, 'targetAttribute' => ['plan_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => Yii::t('app', 'Gói dịch vụ'),
            'user_id' => Yii::t('app', 'User ID'),
            'during_time' => Yii::t('app', 'Thời gian sử dụng'),
            'created_at' => Yii::t('app', 'Thời gian GD'),
            'start_at' => Yii::t('app', 'Bắt đầu'),
            'end_at' => Yii::t('app', 'Kết thúc'),
            'status' => Yii::t('app', 'Trạng thái'),
        ];
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::class, ['id' => 'plan_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}