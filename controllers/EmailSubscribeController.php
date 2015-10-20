<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.07.15
 * Time: 11:08
 */

namespace app\controllers;


use app\models\EmailSubscribe;
use yii\web\Controller;

class EmailSubscribeController extends Controller{


    /*
     * регистрация пользователей на бета-тестирование системы
     */
    public function actionBeta(){

        if(!\Yii::$app->user->isGuest){ $this->redirect('/profile');}

        $model = new EmailSubscribe();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            if($model->ok==0){
                \Yii::$app->getSession()->setFlash('info', 'К сожалению, вы не попали на бета-тест – все места уже заняты. Бла-бла-бла...');
            }else{
                \Yii::$app->getSession()->setFlash('info', 'Вы попали на бета-тестирование. Бла-бла-бла...');
            }

            $model->save();

            return $this->refresh();

        } else {
            return $this->render('beta', [
                'model' => $model,
            ]);
        }

    }
}