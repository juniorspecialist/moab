<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 14:36
 */

namespace app\modules\user\controllers;


use yii\web\NotFoundHttpException;

class SuggestController extends UserMainController{
    /*
     * информация о выборках из БД - Моаб-Метрика
     */
    public function actionIndex()
    {
        //проверка доступа к выборкам для тек. юзера
        $this->access();


        return $this->render('index');
    }

    /*
     * проверка доступа пользователя к БД
     */
    protected function access()
    {
        if(!\app\modules\user\models\User::isSubscribeMoab()){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}