<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.07.15
 * Time: 16:14
 */

namespace app\modules\pay\controllers;


use app\models\Financy;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class WebmoneyController extends Controller{


    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionResult()
    {

        $this->enableCsrfValidation = false;

        \Yii::$app->request->enableCsrfValidation = false;

        \Yii::trace('action result');

        $merchant = \Yii::$app->get('webmoney');


        if( \Yii::$app->request->post('LMI_PREREQUEST')== 1)
        {
            \Yii::trace('LMI_PREREQUEST = 1');


                if (!isset($_REQUEST['LMI_PAYMENT_NO'], $_REQUEST['LMI_PAYMENT_AMOUNT'], $_REQUEST['LMI_PAYEE_PURSE']))
                {
                    die('Не все параметры переданы');
                }

                $financy = $this->loadModel($_POST['LMI_PAYMENT_NO']);


                //проверка на совпадение сумм
                if($financy->amount!=trim($_POST['LMI_PAYMENT_AMOUNT']))
                {
                    die("ERR: НЕВЕРНАЯ СУММА ".$_POST['LMI_PAYMENT_AMOUNT']);
                }
                // Если кошельки не совпадают, то выводим ошибку и прерываем работу скрипта.
                if(trim($_POST['LMI_PAYEE_PURSE'])!=$merchant->LMI_PAYEE_PURSE)
                {
                    die("ERR: НЕВЕРНЫЙ КОШЕЛЕК ПОЛУЧАТЕЛЯ ".$_POST['LMI_PAYEE_PURSE']);
                }
                die("YES");
        }else
        {
            \Yii::trace('LMI_PREREQUEST !== 1');
            //оповещение о платеже от пользователя
            // Склеиваем строку параметров
            $common_string = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].
                $_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].
                $_POST['LMI_SYS_TRANS_DATE'].$merchant->Secret_Key.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
            // Шифруем полученную строку в SHA256 и переводим ее в верхний регистр
            $hash = strtoupper(hash("sha256",$common_string));
            // Прерываем работу скрипта, если контрольные суммы не совпадают
            if($hash!=$_POST['LMI_HASH']) die('Контрольная сумма не совпадает');



            //пополнить баланс юзера и запишим операцию в финан. операции
            $financy = $this->loadModel($_POST['LMI_PAYMENT_NO']);

            $user = $financy->user;

            $financy->status = Financy::STATUS_PAID;
            $financy->balance_after = ($user->balance +  $financy->amount);
            $financy->save();


            $user->updateCounters(['balance'=>$financy->amount]);
        }
    }


    public function actionFail()
    {
        $this->enableCsrfValidation = false;
        \Yii::$app->request->enableCsrfValidation = false;

        \Yii::$app->getSession()->setFlash('error', 'Пополнение баланса не произошло');

        return $this->redirect('/financy');
    }


    public function actionSuccess()
    {

        $this->enableCsrfValidation = false;

        \Yii::$app->request->enableCsrfValidation = false;

        if (!isset($_POST['LMI_PAYMENT_NO']))
        {
            throw new BadRequestHttpException;
        }

        $model = $this->loadModel($_POST['LMI_PAYMENT_NO']);

        if($model->status==Financy::STATUS_PAID)
        {
            \Yii::$app->getSession()->setFlash('success', 'Спасибо, ваш баланс успешно пополнен на '.$model->amount. ' руб.');
        }

        return $this->redirect('/financy');
    }

    /*
     * форма для пополнения балансе через Webmoney
     */
    public function actionIndex()
    {
        $model = new Financy();
        $model->type_operation = Financy::TYPE_OPERATION_PLUS;//операция пополнения
        $model->pay_system = Financy::PAY_SYSTEM_WEBMONEY;
        $model->desc = 'Пополнение баланса через сервис webmoney';

        if ($model->load(\Yii::$app->request->post()) && $model->save())
        {

            $merchant = \Yii::$app->get('webmoney');

            //формируем форму для отправки данных на сайт -мерчанда веб-мани и сразу автоматически, отправляем форму с данными
            return $this->render('_form',['model'=>$model,'id'=>$model->id, 'amount'=>$model->amount,'purse'=>$merchant->LMI_PAYEE_PURSE]);

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
            throw new BadRequestHttpException('Not find Financy, id='.$id);
        }
        return $model;
    }
}