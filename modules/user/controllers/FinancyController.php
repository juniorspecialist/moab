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
use yii\web\NotFoundHttpException;

class FinancyController extends  UserMainController{


    public $defaultAction = 'index';


    public function actionIndex(){

        //$user = $this->loadUser();

        $query = Financy::find()->where(['user_id'=>\Yii::$app->user->id]);
        //$dataProvider = $user->financy->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>false
        ]);

        return
        $this->render('index',[
            //'searchModel' => $searchModel,
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