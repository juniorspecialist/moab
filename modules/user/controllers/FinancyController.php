<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.15
 * Time: 9:53
 */

namespace app\modules\user\controllers;


//use app\models\Financy;
use app\models\Financy;
use app\modules\user\models\User;
use app\modules\user\controllers\UserMainController;

use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class FinancyController extends  UserMainController{


    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public $defaultAction = 'index';


    public function actionIndex(){

        $query = Financy::find()
            ->where(['user_id'=>\Yii::$app->user->id,'status'=>Financy::STATUS_PAID])
            ->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>false
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function loadUser(){
        if (($model = User::findOne(\Yii::$app->user->id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}