<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.11.15
 * Time: 14:45
 */

namespace app\controllers;


use app\models\Financy;
use app\models\UserSubscription;
use app\modules\user\models\User;
use yii\helpers\Url;
use Yii;
use yii\web\Controller;

class BuyController extends Controller {

    /*
     * успешно прошёл платёж, т.е. юзер купил подписку на сайте moab.pro
     */
    public function actionSuccess(){

        //валидируем параметры
        if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue']))
        {
            $this->redirect(Url::to('http://moab.pro/error'));
        }

        $merchant = Yii::$app->get('robokassa');

        $shp  = $this->readParams();


        $row = \Yii::$app->db->createCommand('SELECT * FROM financy_moab WHERE id=:id')->bindValues([':id'=>$_POST['InvId']])->queryOne();

        //проверим статус заявки
        if($row['status']!==Financy::STATUS_PAID){

            //обновим статус заявки
            \Yii::$app->db->createCommand('UPDATE financy_moab SET status=:status WHERE id=:id')->bindValues([':id'=>$_POST['InvId'], ':status'=>Financy::STATUS_PAID])->execute();

            //генерируем случайный пароль для пользователя, который будет использован для регистрации юзера автоматом
            $pass_new = $this->randomPass();

            //если пользователь у нас зареган по почте проверяем - пополняем ему баланс и оформляем подписку
            $user = $this->createUser($row['email'], $pass_new, $row['amount']);

            //оформелеине подписки
            //$this->subscribeUser($user);


        }

        //перенаправим на страницу где видим, что всё успешно
        $this->redirect(Url::to('http://moab.pro/success'));
    }

    /*
     * генерируем случайный пароль для пользователя
     */
    private function randomPass(){
        return strtolower(substr(md5(time()), 0, 6));
    }

    /*
     * изменим параметры для работы с робокассой
     */
    private function getRobokassa(){

        $merchant = Yii::$app->get('robokassa');

        $merchant->sMerchantLogin = 'moabpro';
        $merchant->sMerchantPass1 = 'moabpro1597531';
        $merchant->sMerchantPass2 = 'moabpro20151597531';

        return $merchant;
    }

    /*
     * оформляем подписку юзеру на указанную базу
     * $user - Active Record
     */
//    protected function subscribeUser($user){
//
//        if($user!==null){
//            $financy = new Financy();
//            $financy->amount = (int)$_REQUEST['OutSum'];
//            $financy->create_ad = time();
//            $financy->status = Financy::STATUS_PAID;
//            $financy->balance_after = $user->balance - (int)$_POST['OutSum'];
//            $financy->type_operation = Financy::TYPE_OPERATION_MINUS;
//            $financy->save();
//        }
//    }

    /*
     * создаём пользователя, если пользователь не существует
     * если уже зарен то производим поиск
     */
    protected function createUser($email, $pass, $balance){

        $user = User::findByUsername($email);

        $financy = new Financy();
        $financy->amount = (int)$_POST['OutSum'];
        $financy->create_ad = time();
        $financy->status = Financy::STATUS_PAID;

        //создадим пользователя
        if($user===null){

            $user = new User();
            $user->email = $email;
            $user->setPassword($pass);
            $user->balance = $balance;
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();
            $user->save();

            //отрпавка почты пользователю для подтверждения регистрации
            Yii::$app->mailer->compose(['html'=>'delay_registration_user.php'], ['email' => $user->email, 'pass'=>$pass])
                ->setFrom(['we@moab.pro' => 'MOAB.Pro'])
                ->setTo($this->email)
                ->setSubject('Регистрация в личном кабинете MOAB.pro')
                ->send();

            //
            $financy->balance_after = (int)$_POST['OutSum'];

            //отправка письма с данными доступа для пользователя
        }else{

            $financy->balance_after = $user->balance + (int)$_POST['OutSum'];

            //обновим баланс пользователя
            $user->updateCounters(['balance' => (int)$_POST['OutSum']]);
        }

        //записали пополнение в фин. операции юзера
        $financy->save();


        return $user;
    }


    /*
     * ошибка при оплате возникла
     */
    public function actionError(){
        //перенаправим на страницу где видим, страница ошибка
        $this->redirect(Url::to('http://moab.pro/error'));
    }

    public function readParams(){

        $shp = [];

        foreach ($_REQUEST as $key => $param) {
            if (strpos(strtolower($key), 'shp') === 0) {
                $shp[$key] = $param;
            }
        }

        return $shp;
    }
}