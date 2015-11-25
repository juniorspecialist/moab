<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.05.15
 * Time: 8:40
 */

return [

    '' => 'user/default/index',
    'link/<link:\w+>' => 'user/default/link',
    '<_a:(login|logout|signup|confirm-email|request-password-reset|reset-password|change-password|profile|captcha|info)>' => 'user/default/<_a>',

    'doc'=>'user/doc/index',
    'doc/<_a>' => 'user/doc/<_a>',

    'ticket' => 'ticket/ticket/index',
    'ticket/<_a:(create)>' => 'ticket/ticket/<_a>',
    [
        'pattern' => 'ticket/view/<id:\d+>',
        'route' => 'ticket/ticket/view',
        'suffix' => ''
    ],


    'suggest' => 'user/suggest/index',
    'suggest/<_a:(create)>' => 'user/suggest/<_a>',
    'suggest-main' => 'user/suggest-main/index',
    'suggest-main/<_a:(create)>' => 'user/suggest-main/<_a>',

//    [
//        'pattern' => 'ticket/view/<id:\d+>',
//        'route' => 'ticket/ticket/view',
//        'suffix' => ''
//    ],



    'financy' => 'user/financy/index',
    'subscription' => 'user/subscription/index',
    'rdp' => 'user/subscription/rdp',
    'subscribe'=>'user/subscription/subscribe',
    'pay'=>'pay/default/index',
    'pay/robokassa'=>'pay/robokassa/index',

     'api/api.php'=>'api/subscribe',

     'beta'=>'email-subscribe/beta',


    //'admin/<_a:(users)>' => 'admin/default/<_a>',

//    'ticket'=>'ticket/default/index',
//    [
//        'pattern' => 'ticket/<id:\d+>',
//        'route' => 'ticket/default/view',
//        'suffix' => ''
//    ],
//    'ticket/<_a>' => 'ticket/default/<_a>',


//    'tasks'=>'tasks/index',
//    [
//        'pattern' => 'tasks/<link:\w+>',
//        'route' => 'tasks/view',
//        'suffix' => ''
//    ],

    //'financy'=>'financy/index',
//    [
//        'pattern' => 'tasks/<link:\w+>',
//        'route' => 'tasks/view',
//        'suffix' => ''
//    ],


//    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//    '<controller:\w+>/<action:\w+>/<link:\w+>' => '<controller>/<action>',
//    '<controller:\w+>/<action:\w+>/<file:\w+>' => '<controller>/<action>',
//    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',


    [
        'pattern' => '<controller>/<action>/<id:\d+>',
        'route' => '<controller>/<action>',
        'suffix' => ''
    ],
    [
        'pattern' => '<controller>/<action>',
        'route' => '<controller>/<action>',
        'suffix' => ''
    ],
    [
        'pattern' => '<module>/<controller>/<action>/<id:\d+>',
        'route' => '<module>/<controller>/<action>',
        'suffix' => ''
    ],
    [
        'pattern' => '<module>/<controller>/<action>',
        'route' => '<module>/<controller>/<action>',
        'suffix' => ''
    ],
];