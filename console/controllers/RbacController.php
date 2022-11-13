<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        // ====== PERMISSION ===========
        // add "accessAdmin" permission
        // $accessAdmin = $auth->createPermission('accessAdmin');
        // $accessAdmin->description = 'Access the Admin Panel';
        // $auth->add($accessAdmin);

        // $accessAdmin = $auth->getPermission('accessAdmin');
        
        // $editor = $auth->getRole('editor');
        // $admin = $auth->getRole('admin');
        // $auth->addChild($editor, $accessAdmin);
        // $auth->addChild($admin, $accessAdmin);
    }
}