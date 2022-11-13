<?php
use \yii\web\Request;
$request = new Request();
$baseUrl = preg_replace('/\/(backend|frontend)\/web/', '', $request->getBaseUrl());
$adminUrl = $baseUrl.'/admin';
return [
    'name' => 'app-backend',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',

        '@uploaddir' => 'upload',
        '@baseUrl' => $baseUrl,
        '@adminUrl' => $adminUrl,
        '@upload' => '@frontend/web/upload',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
           'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
       ],
    ],
    /*     'as access' => [
            'class' => 'mdm\admin\components\AccessControl',
            'allowActions' => [
                'site/*',
                //'admin/*',
            ]
        ], */
];
