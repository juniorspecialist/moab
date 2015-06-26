<?php

namespace app\modules\user\controllers;

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
            //return $this->goBack();
            //return $this->redirect(['profile']);
            if(Yii::$app->user->identity->isAdmin()){
                return \Yii::$app->response->redirect('admin/default/users');
            }else{
                return \Yii::$app->response->redirect('/profile');
            }

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Подтвердите ваш электронный адрес.');
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionConfirmEmail($token)
    {
        try {
            $model = new ConfirmEmailForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->confirmEmail()) {
            Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш Email успешно подтверждён.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Ошибка подтверждения Email.');
        }

        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
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
