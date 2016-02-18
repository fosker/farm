<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php')
);

$config =  [
    'id' => 'app-backend',
    'language'=>'ru',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'layout'=>'admin',
    'defaultRoute' => 'main/index',
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru_RU',
        ],
        'admin' => [
            'class' => 'yii\web\User',
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl'=>['auth/login'],
            'idParam'=>'__adminId',
            'identityCookie'=>[
                'name' => '_admin',
            ],
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
        'request' => [
            'cookieValidationKey' => 'm13suiePCS1k0k6G3_Nan2NQkOKPGMDr',
        ],
    ],
    'params' => $params,
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;