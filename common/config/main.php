<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=shop4uby_farm',
            'username' => 'shop4uby_farm',
            'password' => 'Bosingwa',
//            'dsn' => 'mysql:host=localhost;dbname=farm',
//            'username' => 'root',
//            'password' => '',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@backend/mail',
            'htmlLayout' => false,
            'useFileTransport' => false
        ],
    ],
];