<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','debug'],
    'name'=>' MOAB LK',//личный кабинет для проекта MOAB
    'timeZone'=>'Europe/Moscow',
    'language' => 'ru',
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
    ],

    'components' => [

        'robokassa' => [
            'class' => 'app\models\Robokassa',
            'sMerchantLogin' => 'Mykeywordsru',
            'sMerchantPass1' => 'paroler159753',
            'sMerchantPass2' => 'paroler123',
            'testMode'=>true,//используем тестовый режим для отладки
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '_LdzbzLns4JMiQYQtoHWEDTA0-Td1Jjq',
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
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/default/login'],
            //'admins'=>['admin'],
            'on afterLogin'=>function ($event) {
                app\modules\user\models\User::afterLogin();
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
            'useFileTransport' => true,
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
