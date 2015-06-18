<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.05.15
 * Time: 8:40
 */

return [

    '' => 'site/index',
    'about'=>'site/about',
    'contact'=>'site/contact',

    '<_a:(login|logout|signup|confirm-email|request-password-reset|reset-password|change-password|profile|captcha)>' => 'user/default/<_a>',

    'ticket'=>'ticket/default/index',
    [
        'pattern' => 'ticket/<id:\d+>',
        'route' => 'ticket/default/view',
        'suffix' => ''
    ],
    'ticket/<_a>' => 'ticket/default/<_a>',


    'tasks'=>'tasks/index',
    [
        'pattern' => 'tasks/<link:\w+>',
        'route' => 'tasks/view',
        'suffix' => ''
    ],

    'financy'=>'financy/index',
    [
        'pattern' => 'tasks/<link:\w+>',
        'route' => 'tasks/view',
        'suffix' => ''
    ],


    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>/<link:\w+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>/<file:\w+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',


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