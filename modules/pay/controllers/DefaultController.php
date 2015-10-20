<?php

namespace app\modules\pay\controllers;

use app\modules\user\controllers\UserMainController;
use app\modules\user\models\PromoUser;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends UserMainController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['result','success','fail', 'index'],
                'rules' => [
//                    [
//                        'actions' => ['result','success','fail'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        //активации подписки по промо-коду
        $promo = new PromoUser();

        if ($promo->load(\Yii::$app->request->post()) && $promo->validate()) {

            $promo->activate();

            \Yii::$app->getSession()->setFlash('success', 'Активация по подписке прошла успешно');

            return $this->refresh();
        }


        return $this->render('index', ['model'=>$promo]);
    }
}
