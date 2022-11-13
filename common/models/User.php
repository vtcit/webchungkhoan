<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $display_name
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $role; // roles of user
    public $new_password;
    public $repeat_new_password;

    private static $_availableRoles;

    public function init() {
        $this->role = array_keys($this->getRoles());
    }

    public static function getStatuses() {
        return [
            // 0 => Yii::t('app', 'Deleted'),
            9 => Yii::t('app', 'Inactive'),
            10 => Yii::t('app', 'Active'),
        ];
    }
    public static function getAvailableRoles() {
        return Yii::$app->authManager->getRoles();
    }
    public static function getAvailableRolesList() {
        if(static::$_availableRoles !== null) {
            return static::$_availableRoles;
        }

        $availableRoles = static::getAvailableRoles();
        static::$_availableRoles = [];
        if(!empty($availableRoles)) {
            foreach($availableRoles as $key => $role) {
                static::$_availableRoles[$key] = ucfirst($key);
            }
        }
        return static::$_availableRoles;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['display_name', 'email'], 'string', 'max' => 250],
            ['email', 'email'],
            ['email', 'unique'],
            ['role', 'required'],
            // ['role', 'each', 'rule' => ['safe']],
            // ['role', 'default', 'value' => 'member'],
            [['new_password', 'repeat_new_password'], 'string'],
            ['repeat_new_password','compare','compareAttribute' => 'new_password',
                'message' => Yii::t('app', 'Passwords dont match!'), 'skipOnEmpty' => false,
                'when' => function ($model) {
                    return $model->new_password !== null && $model->new_password !== '';
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'display_name' => Yii::t('app', 'Display name'),
            'created_at' => Yii::t('app', 'Created at'),
            'role' => Yii::t('app', 'Role'),
            'email' => Yii::t('app', 'Email'),
            'new_password' => Yii::t('app', 'New Password'),
            'repeat_new_password' => Yii::t('app', 'Repeat New Password'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return Yii::$app->authManager->getRolesByUser($this->id);
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    /**
     * Gets query for [[PlanUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlanUsers()
    {
        return $this->hasMany(PlanUser::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Plans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlans()
    {
        return $this->hasMany(Plan::class, ['id' => 'plan_id'])->viaTable('{{%plan_user}}', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }

    public function updateRole($roles = null) {
         // Ignore Supper Admin == 1
        if($this->id == 1) {
            Yii::$app->getSession()->setFlash( 'error', 'Bạn không đủ quyền thay đổi Super Admin');
            return;
        }
        if($roles === null) {
            $roles = $this->role;
        }

        if(!is_object($roles) && !is_string($roles)){
            return;
        }

        $authManager = Yii::$app->authManager;
        // $availableRoles = $model::getAvailableRoles();
        // $model->role is empty, not change current roles
        if(!is_object($roles)) {
            $roles = [$roles];
        }
        $newRoles = [];
        foreach($roles as $r)
        {
            if($newRole = $authManager->getRole($r)) {
                $newRoles[] = $newRole;
            }
        }
        if(!empty($newRoles)) {
            // Remove all old roles
            $authManager->revokeAll($this->id);
            // assign new roles
            foreach($newRoles as $newRole) {
                $authManager->assign($newRole, $this->id);
            }
        }
        // Yii::$app->session->setFlash('error', $error);

    }
}
