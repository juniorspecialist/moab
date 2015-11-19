<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.05.15
 * Time: 13:42
 */

namespace app\modules\pay\controllers;

use app\models\Financy;
//use app\modules\user\controllers\UserMainController;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use app\models\Robokassa;

class RobokassaController extends Controller{


//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'only' => ['result','success','fail', 'index'],
//                'rules' => [
//                    [
//                        'actions' => ['result','success','fail'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
//                    [
//                        'actions' => ['index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
//    }

    public function readParams(){

        $shp = [];

        foreach ($_REQUEST as $key => $param) {
            if (strpos(strtolower($key), 'shp') === 0) {
                $shp[$key] = $param;
            }
        }

        return $shp;
    }

    public function actionResult(){

        if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue']))
        {
            throw new BadRequestHttpException;
        }

        $merchant = Yii::$app->get('robokassa');

        $shp = $this->readParams();

        if ($merchant->checkSignature($_REQUEST['SignatureValue'], $_REQUEST['OutSum'], $_REQUEST['InvId'], $merchant->sMerchantPass2, $shp)) {

            //заявка на пополнение баланса
            $financy = $this->loadModel($_REQUEST['InvId']);

            if($financy->status!==Financy::STATUS_PAID)
            {

                $financy->status = Financy::STATUS_PAID;

                //пополним баланс пользователя
                $user = $financy->user;

                $financy->balance_after = $user->balance + (int)$_REQUEST['OutSum'];

                $financy->update();

                $user->updateCounters(['balance' => (int)$_REQUEST['OutSum']]);

                return 'OK'.$financy->id;
            }else{
                throw new BadRequestHttpException;
            }

        }else{
            throw new BadRequestHttpException;
        }
    }

    public function actionSuccess(){

        if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue'])){
            throw new BadRequestHttpException;
        }

        $merchant = Yii::$app->get('robokassa');

        $shp  = $this->readParams();

        if ($merchant->checkSignature($_REQUEST['SignatureValue'], $_REQUEST['OutSum'], $_REQUEST['InvId'], $merchant->sMerchantPass1, $shp)){

            //заявка на пополнение баланса
            $financy = $this->loadModel($_REQUEST['InvId']);

            if($financy->status == Financy::STATUS_PAID)
            {

                Yii::$app->getSession()->setFlash('success', 'Спасибо, ваш баланс успешно пополнен.');
            }else{
                return 'Статус заявки не соотвествует';
            }

            return $this->redirect('/financy');
        }else{
            throw new BadRequestHttpException;
        }
    }

    public function actionFail(){

        if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId']))
        {
            throw new BadRequestHttpException;
        }

        $shp  = $this->readParams();

        $model = $this->loadModel($_REQUEST['InvId']);

        if ($model)
        {
            $model->status = Financy::STATUS_CANCEL_PAID;

            $model->update();

            //return 'Ok';
            Yii::$app->getSession()->setFlash('error', 'Вы отказались от платежа.');

            return $this->redirect('/financy');
        } else {
            return 'Status has not changed';
        }
    }

    /*
     * форма пополнения через робокассу
     */
    public function actionIndex(){

        $model = new Financy();

        $model->type_operation = Financy::TYPE_OPERATION_PLUS;//операция пополнения

        $model->pay_system = Financy::PAY_SYSTEM_ROBOKASSA;

        $model->desc = 'Пополнение баланса через сервис Робокасса';

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {

            $merchant = Yii::$app->get('robokassa');

            //формируем ссылку на оплату+ делаем редирект
            return $merchant->payment($model->amount, $model->id, 'Пополнение счета', null, Yii::$app->user->identity->email);

        } else {
            return $this->render('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param integer $id
     * @return Financy
     * @throws \yii\web\BadRequestHttpException
     */
    protected function loadModel($id) {
        $model = Financy::findOne($id);
        if ($model === null) {
            throw new BadRequestHttpException;
        }
        return $model;
    }
}