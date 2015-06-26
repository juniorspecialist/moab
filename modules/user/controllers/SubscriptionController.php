<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.15
 * Time: 9:30
 */

namespace app\modules\user\controllers;


use app\models\Base;
use app\models\UserSubscription;
use yii\data\ActiveDataProvider;

class SubscriptionController extends UserMainController{

    /*
     * список подписок на которые юзер может подписаться+выделим на которые он подписан
     */
    public function actionIndex(){

        $dataProvider = new ActiveDataProvider([
            'query' => Base::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        //список всех подписок юзера
        $subsriptions = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->all();

        return $this->render('index', ['dataProvider'=>$dataProvider, 'subsriptions'=>$subsriptions]);
    }

    /*
     * оформление подписки пользователя
     */
    public function actionSubscribe(){


        echo '<pre>'; print_r($_POST);
    }


}