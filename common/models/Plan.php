<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "xz9i2_plan".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $desc_short
 * @property int $during_time
 * @property int $status
 *
 * @property PlanUser[] $planUsers
 * @property User[] $users
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%plan}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
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
            [['name', 'during_time'], 'required'],
            [['description', 'desc_short'], 'string'],
            [['during_time', 'status'], 'integer'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'desc_short' => Yii::t('app', 'Desc Short'),
            'during_time' => Yii::t('app', 'During Time'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * Gets query for [[PlanUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUsers()
    {
        return $this->hasMany(PlanUser::class, ['plan_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable(PlanUser::class, ['plan_id' => 'id']);
    }
}
