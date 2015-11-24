<?php

namespace app\modules\user\controllers;



use app\models\Access;
use app\models\Action;
use app\models\Links;

use app\modules\user\models\PasswordChangeForm;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use app\modules\user\controllers\UserMainController;

use app\modules\user\models\ConfirmEmailForm;
use app\modules\user\models\LoginForm;
use app\modules\user\models\PasswordResetRequestForm;
use app\modules\user\models\ResetPasswordForm;
use app\modules\user\models\SignupForm;
use yii\base\InvalidParamException;

use yii\web\BadRequestHttpException;

use Yii;
use yii\web\Cookie;


class DefaultController extends UserMainController
{

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){


        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('/admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }
        }

        $login = new LoginForm();

        $signup = new SignupForm();

        return $this->render('index', ['login'=>$login,'signup'=>$signup]);
    }

   public function actionInfo()
   {
        $model = User::findOne(['id'=>Yii::$app->user->id]);

        return $this->render('info', [
            'user' => $model,
        ]);
   }

    public function actionMoab(){
        if(Yii::$app->request->isPost){

            if( $curl = curl_init() ) {
                curl_setopt($curl, CURLOPT_URL, 'http://moab.pro');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $_POST);
                $out = curl_exec($curl);
                //echo $out;
                curl_close($curl);

                //подпишим юзера на Unisender, если он согласился
                if(Yii::$app->request->post('news'))
                {
                    User::sendUnisenderSebscribe(Yii::$app->request->post('email'), Yii::$app->request->post('name'));
                }
            }
        }else{
            throw new BadRequestHttpException();
        }
    }

    public function actionLogin()
    {


        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }
        }

        $model = new LoginForm();

        //валидация параметров формы и авторизация
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

	        return 'ok'; Yii::$app->end();

        } else {
            return $this->renderAjax('_login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLink(){
        if(Yii::$app->request->get('link')){
            //нет куки, если куку больше никому не добавляли - запишим тек. юзеру
            if (!isset(Yii::$app->request->cookies['link'])) {
                $link = Links::findOne(['link'=>Yii::$app->request->get('link'), 'status'=>Links::STATUS_IS_NEW]);
                if($link){
                    //запишим куку тек.. юзеру
                    $cookie = new Cookie([
                        'name' => 'link',
                        'value' => Yii::$app->request->get('link'),
                        'expire' => time() + 86400 * 14,//2 недели
                        //'domain' => '.example.com' // <<<=== HERE
                    ]);
                    \Yii::$app->getResponse()->getCookies()->add($cookie);
                }else{
                    Yii::$app->getSession()->setFlash('error', 'Ссылка уже была использована для регистрации. Повторное использование невозможно');
                }
            }
        }

        $this->redirect('/');
    }


    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('/admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Будьте добры, подтвердите ваш электронный адрес. Для этого проверьте почту, указанную при регистрации и перейдите по ссылке, указанной в письме. Если письмо долго не приходит - проверьте папку «Спам», возможно оно попало туда.');
                //return $this->goHome();
               //return $this->redirect(['/user/default/index']);
	            return 'ok';Yii::$app->end();
            }
        }

        return $this->renderAjax('_signup', [
            'model' => $model,
        ]);
    }

    public function actionConfirmEmail($token)
    {

        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('/admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }
        }

        try {
            $model = new ConfirmEmailForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш Email успешно подтверждён. Введите почту, указанную при регистрации, в поле "Логин" и созданный вами пароль для авторизации в личном кабинете.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения Email.');
        }

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {

        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('/admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }
        }

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Спасибо! На ваш Email было отправлено письмо со ссылкой на восстановление пароля.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Извините. У нас возникли проблемы с отправкой.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('/admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }
        }

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Пароль успешно изменён.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /*
     * информация о профиле пользователя
     */
    public function actionProfile(){


        $model = User::findOne(['id'=>Yii::$app->user->id]);

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /*
     * смена пароля
     */
    public function actionChangePassword(){

        $model = new PasswordChangeForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->setNewPassword();

            Yii::$app->getSession()->setFlash('success', 'Спасибо! Успешно обновили пароль.');

            return $this->refresh();

        } else {
            return $this->render('passwordChange', [
                'model' => $model,
            ]);
        }
    }
}
