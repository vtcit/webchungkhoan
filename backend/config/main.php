<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
/* 		'admin' => [
			'class' => 'mdm\admin\Module',
			'layout' => 'left-menu',
		], */
	],
    'components' => [
        'request' => [
			'baseUrl' => $adminUrl,
            'csrfParam' => '_csrf-backend',
        ],
        'assetManager' => [
            'assetMap' => [
                //'jquery.js' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js',
                //'bootstrap.js' => 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js',
                //'bootstrap.css' => 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css',
                //'font-awesome.min.css' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            // 'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'backend-session',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'baseUrl' => $adminUrl,
            'rules' => require __DIR__ . '/urls.php',
        ],
        'urlManagerFrontend' => [
			'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'baseUrl' => $baseUrl,
            'rules' => require __DIR__ . '/../../frontend/config/urls.php',
        ],
    ],
	'as beforeRequest' => [
		'class' => 'yii\filters\AccessControl',
		'rules' => [
			[
				'allow' => true,
				'actions' => ['login', 'signup'],
			],
			[
				'allow' => true,
				'roles' => ['@'],
			],
		],
		'denyCallback'  => function ($rule, $action) {
			\Yii::$app->session->setFlash('error', \Yii::t('app', 'Please login first to continue, or create an account.'));
            
			\Yii::$app->user->loginRequired();
		},
	],
    'params' => $params,
];
