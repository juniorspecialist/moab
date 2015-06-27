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

        //отправили запрос на подписку
        if(\Yii::$app->request->isPjax)
        {
            $modelSubscription = new UserSubscription();

            if ($modelSubscription->load(\Yii::$app->request->post()) && $modelSubscription->validate()) {

                if($modelSubscription->save()){
                    \Yii::$app->getSession()->setFlash('success', 'Успешно оформили подписку.');
                }else{

                    \Yii::$app->getSession()->setFlash('error', print_r($modelSubscription->errors, true));
                }

            }else{
                $error_msg = '';
                foreach($modelSubscription->errors as $error){
                    $error_msg.=$error[0].'<br>';
                }
                \Yii::$app->getSession()->setFlash('error', $error_msg);
            }

            return $this->refresh();
        }


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

//    /*
//     * оформление подписки пользователя
//     */
//    public function actionSubscribe(){
//
//        $model = new UserSubscription();
//
//        $subsriptions = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->all();
//
//        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
//            \Yii::$app->getSession()->setFlash('success', 'Успешно добавили новую запись.');
//            //return $this->redirect(['index']);
//
//            $dataProvider = new ActiveDataProvider([
//                'query' => Base::find(),
//                'pagination' => [
//                    'pageSize' => 20,
//                ],
//            ]);
//            //список всех подписок юзера
//            $subsriptions = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->all();
//
//            return $this->render('index', ['dataProvider'=>$dataProvider, 'subsriptions'=>$subsriptions]);
//
//            //return $this->render('_subscribe',['subsriptions'=>$subsriptions, 'model'=>Base::findOne($model->base_id), 'index'=>0]);
//        } else {
////            return $this->render('create', [
////                'model' => $model,
////            ]);
//
//            \Yii::$app->getSession()->setFlash('error', print_r($model->errors, true));
//            //return 'errors';
//            return $this->render('_subscribe',['subsriptions'=>$subsriptions, 'model'=>Base::findOne($model->base_id), 'index'=>0]);
////            $dataProvider = new ActiveDataProvider([
////                'query' => Base::find(),
////                'pagination' => [
////                    'pageSize' => 20,
////                ],
////            ]);
////            //список всех подписок юзера
////            $subsriptions = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->all();
////
////            return $this->render('index', ['dataProvider'=>$dataProvider, 'subsriptions'=>$subsriptions]);
//        }
//
//
//        //echo '<pre>'; print_r($_POST);
//    }


}