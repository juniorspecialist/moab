<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.11.15
 * Time: 14:45
 */

namespace app\controllers;


use app\models\Category;
use app\models\Financy;
use app\models\UserSubscription;
use app\modules\user\models\User;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class BuyController extends Controller {


    public function beforeAction($action)
    {
        // ...set `$this->enableCsrfValidation` here based on some conditions...
        // call parent method that will check CSRF if such property is true.
        if ($action->id === 'success' || $action->id === 'result') {
            # code...
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    /*
     * проверяем статус счёта, по которому юзер пытается совершить оплату
     */
    public function actionResult(){


        $this->enableCsrfValidation = false;

        if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue']))
        {
            $this->redirect(Url::to('http://moab.pro/error'));
        }

        $merchant = $this->getRobokassa();

        $shp = $this->readParams();

        if ($merchant->checkSignature($_REQUEST['SignatureValue'], $_REQUEST['OutSum'], $_REQUEST['InvId'], $merchant->sMerchantPass2, $shp)) {

            //заявка на пополнение баланса
            $row = \Yii::$app->db2->createCommand('SELECT * FROM financy_moab WHERE id=:id')->bindValues([':id'=>$_POST['InvId']])->queryOne();

            if($row['status']!==Financy::STATUS_PAID)
            {

                //обновим статус заявки
                \Yii::$app->db2->createCommand('UPDATE financy_moab SET status=:status WHERE id=:id')->bindValues([':id'=>$_POST['InvId'], ':status'=>Financy::STATUS_PAID])->execute();

                //генерируем случайный пароль для пользователя, который будет использован для регистрации юзера автоматом
                $pass_new = $this->randomPass();

                //если пользователь у нас зареган по почте проверяем - пополняем ему баланс и оформляем подписку
                $this->createUser($row['email'], $pass_new, $row['amount']);

                return 'OK'.$row['id'];

            }else{
                //throw new BadRequestHttpException('Проблема в статусе счёта');
                Yii::info('Проблема в статусе счёта', 'suggest_result_'.$row['id']);
            }

        }else{
            //throw new BadRequestHttpException('Проблема в контрольной сумме');
            Yii::info('Проблема в контрольной сумме', 'suggest_result');
        }
    }


    /*
     * успешно прошёл платёж, т.е. юзер купил подписку на сайте moab.pro
     */
    public function actionSuccess(){


        $this->enableCsrfValidation = false;

        //валидируем параметры
        if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue']))
        {
            Yii::info(Json::encode($_POST), 'suggest_success_error');

            $this->redirect(Url::to('http://moab.pro/error'));
        }

        $merchant = $this->getRobokassa();

        Yii::info(Json::encode($_POST), 'suggest_success_robokassa');

        $shp  = $this->readParams();


        $row = \Yii::$app->db2->createCommand('SELECT * FROM financy_moab WHERE id=:id')->bindValues([':id'=>$_POST['InvId']])->queryOne();

        //проверим статус заявки

        if($row['status']==Financy::STATUS_PAID){
            //перенаправим на страницу где видим, что всё успешно
            $this->redirect(Url::to('http://moab.pro/success'));
        }

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
        $merchant->sMerchantPass1 = 'moabpro159753';
        $merchant->sMerchantPass2 = 'moabpro2015159753';

        return $merchant;
    }

    /*
     * создаём пользователя, если пользователь не существует
     * если уже зарен то производим поиск
     */
    protected function createUser($email, $pass, $balance){

        $user = User::findByUsername($email);

        $financy = new Financy();
        $financy->amount = (int)$_POST['OutSum'];
        $financy->type_operation = Financy::TYPE_OPERATION_PLUS;
        $financy->desc = 'Пополнение баланса через сервис Robokassa';
        $financy->pay_system = Financy::PAY_SYSTEM_ROBOKASSA;
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


            //добавим группу пользователю по-умолчанию
            $category = new Category();
            $category->user_id = $user->id;
            $category->title = 'Без группы';
            $category->save();

            //отрпавка почты пользователю для подтверждения регистрации
            Yii::$app->mailer->compose(['html'=>'delay_registration_user.php'], ['email' => $user->email, 'pass'=>$pass])
                ->setFrom(['we@moab.pro' => 'MOAB.Pro'])
                ->setTo($user->email)
                ->setSubject('Регистрация в личном кабинете MOAB.pro')
                ->send();

            //
            $financy->balance_after = (int)$_POST['OutSum'];

            //отправка письма с данными доступа для пользователя
        }else{

            $financy->balance_after = $user->balance + (int)$_POST['OutSum'];

            //обновим баланс пользователя
            $user->updateCounters(['balance' => (int)$_POST['OutSum']]);

            //
            //отрпавка письма пользователю для подтверждения факта пополнения баланса
            Yii::$app->mailer->compose(['html'=>'user_buy_suggest_user_exist.php'], ['email' => $user->email])
                ->setFrom(['we@moab.pro' => 'MOAB.Pro'])
                ->setTo($user->email)
                ->setSubject('Ваш баланс в сервисе MOAB пополнен!')
                ->send();
        }

        //подвязываем операцию к пользователю
        $financy->user_id = $user->id;

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