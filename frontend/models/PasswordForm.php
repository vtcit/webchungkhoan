<?php
    namespace frontend\models;
   
    use Yii;
    use yii\base\Model;
    use common\models\Login;
   
    class PasswordForm extends Model{
        public $old_password;
        public $new_password;
        public $repeat_new_password;
       
        public function rules(){
            return [
                [['old_password', 'new_password', 'repeat_new_password'], 'required'],
                ['old_password', 'findPasswords'],
                ['repeat_new_password','compare','compareAttribute'=>'new_password'],
            ];
        }
       
        public function findPasswords($attribute, $params=[]){
            $user = Yii::$app->user->identity;
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Old password is incorrect.');
            }
        }
       
        public function attributeLabels(){
            return [
                'old_password'=>'Old Password',
                'new_password'=>'New Password',
                'repeat_new_password'=>'Repeat New Password',
            ];
        }
    }