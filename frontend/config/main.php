<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
			'baseUrl' => $baseUrl,
            'csrfParam' => '_csrf-frontend',
        ],
        'assetManager' => [
            'bundles' => [
                        'yii\web\JqueryAsset' => [
                            'js' => [
                                YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                            ]
                        ],
                        'yii\bootstrap5\BootstrapAsset' => [
                            'css' => [
                                YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                            ]
                        ],
                        'yii\bootstrap5\BootstrapPluginAsset' => [
                            'js' => [
                                YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                            ]
                        ]
            ],
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
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'session-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'suffix' => '/',
            'showScriptName' => false,
			'baseUrl' => $baseUrl,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
            ],
            'rules' => require __DIR__ . '/urls.php',
        ],
        'urlManagerBackend' => [
			'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'baseUrl' => $adminUrl,
            'rules' => require __DIR__ . '/../../backend/config/urls.php',
        ],
    ],
    'params' => $params,
];
