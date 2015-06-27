<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.15
 * Time: 14:18
 */

namespace app\modules\user\controllers;


use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UserMainController extends Controller{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup','admin','profile'],
                'rules' => [
                    [
                        'actions' => ['signup','request-password-reset','login','result'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','profile','financy','login', 'index','subscribe'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
//                    [
//                        'actions' => ['logout','profile'],
//                        'deny' => true,
//                        'roles' => ['@'],
//                    ],

//                    [
//                        'actions' => ['admin'],
//                        'allow' => true,
//                        //'roles' => ['@'],
//                        'matchCallback' => function() {
//                            if(Yii::$app->user->identity && Yii::$app->user->identity->isAdmin()){
//                                return true;
//                            }
//                            return false;
//                        }
//                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
}