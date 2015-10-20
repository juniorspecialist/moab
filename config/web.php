<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','debug'],
    'name'=>' MOAB LK',//личный кабинет для проекта MOAB
    'timeZone'=>'Europe/Moscow',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru',
    'defaultRoute' => 'user/default/profile',
    'modules' => [

        'debug' => 'yii\debug\Module',

        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],

        'pay' => [
            'class' => 'app\modules\pay\Module',
        ],
        'ticket' => [
            'class' => 'app\modules\ticket\Module',
        ],

        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'] // adjust this to your needs
        ],

    ],

    'components' => [

        'formatter' => [
            //'dateFormat' => 'dd.MM.yyyy',
            //'locale'=>'ru-RU',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',

        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_LdzbzLns4JMiQYQtoHWEDTA0-Td1Jjq',
            //'enableCsrfValidation' => true,
        ],
        'sypexGeo' => [
            'class' => 'omnilight\sypexgeo\SypexGeo',
            'database' => '@app/data/SxGeoCity.dat',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => //[
                require(__DIR__ . '/urls.php'),
                //['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
            //],
        ],

        'cache' => [
            //'class' => 'yii\caching\FileCache',
            'class'=>'yii\redis\Cache',
        ],

        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],

        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port' => 6379,
                //'database' => 0,
            ],
        ],
        
	    'robokassa' =>  require(__DIR__ . '/robokassa_config.php'),

        //подключим настройки доступов и паролей для робокассы
        'webmoney' =>  require(__DIR__ . '/webmoney_config.php'),
        
        
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/default/index'],
            //'admins'=>['admin'],
            'on afterLogin'=>function ($event) {
                app\modules\user\models\User::afterLogin();
            },
            'on beforeLogin'=>function($event){
                app\modules\user\models\User::beforeLogin($event);
            }

        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            
            'messageConfig' => [
                'from' => 'we@moab.pro',
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

//установка некоторых параметров общего назначения по умолчанию
\Yii::$container->set('yii\data\Pagination', [
    'pageSize' => 50,
]);

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
