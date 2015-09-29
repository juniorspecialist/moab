<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.15
 * Time: 9:59
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\components\widgets\Alert;
use yii\bootstrap\Modal;

/*
 * Пользователь не должен без подписки в списке баз видеть Moab Base и Moab Pro и иметь возможность на них подписаться самостоятельно – на них подписку осуществляет только админ.
 */


$subs = new \app\models\UserSubscription();

$uid = uniqid('form_'.$model->id);

$find = false;

if($subsriptions){

    foreach($subsriptions as $subsription){
        if($subsription->base_id==$model->id){
            $find = true;
            $subs = $subsription;break;
        }
    }
}

if(!in_array($model->id, $except_list)){
    if($model->id==Yii::$app->params['subscribe_moab_base_id'] ||$model->id==Yii::$app->params['subscribe_moab_pro_id'])
    {
        if(\app\modules\user\models\User::isSubscribeMoab() && $find)
        {

            echo $this->render('_subscribe_form', ['subs'=>$subs, 'model'=>$model,'index'=>$index, 'moab_base'=>true]);
        }

    }else{
        echo $this->render('_subscribe_form', ['subs'=>$subs, 'model'=>$model,'index'=>$index, 'moab_base'=>false]);
    }
}

