<?php

namespace app\modules\admin\controllers;

use app\components\MainHelper;
use app\models\AuthLog;
use app\models\AuthLogQuery;
use app\models\Base;
use app\models\Category;
use app\models\Financy;
use app\models\UserAccess;
use app\models\UserSubscription;
use app\modules\admin\controllers\BaseAdminController;
use app\modules\user\models\AddBalanceUserForm;
use app\modules\user\models\ChangePasswordForm;
use app\modules\user\models\ChangeStatusForm;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends BaseAdminController
{

    /*
     * отображаем список пользователей
     */
    public function actionUsers()
    {
        $searchModel = new UserSearch();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('table_users', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }


    /*
     * информация о пользователе
     * доступы по подписке+ список подписок
     */
    public function actionInfoUser($id)
    {
        $user = $this->loadUser($id);

        return $this->render('info_user',['user'=>$user]);
    }

    /*
     * фун-я возврата суммы по подписке юзеру
     * $id  - ID подписки
     */
    public function actionReturnSumSubscribe($id)
    {
        if(\Yii::$app->request->isPost)
        {
            $subscribe = UserSubscription::findOne($id);

            if($subscribe)
            {
                UserSubscription::returnBalanceBySubscribe($subscribe);

                return $this->redirect(\Yii::$app->request->referrer);
            }
        }else{
            throw new BadRequestHttpException('Only post request.');
        }
    }


    /*
     * пополнение баланса пользователю
     */
    public function actionAddBalance($id){

        $model = new AddBalanceUserForm();

        $user = $this->loadUser($id);

        $model->user_id = $user->id;


        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            //запишим операцию пополнения в общий лог. фин. операций
            $financy = new Financy();
            $financy->user_id = $user->id;
            $financy->amount = $model->amount;
            $financy->balance_after = ($user->balance + $model->amount);
            $financy->type_operation = Financy::TYPE_OPERATION_PLUS;
            $financy->status = Financy::STATUS_PAID;
            $financy->desc = 'Пополнение от администрации сайта';
            $financy->pay_system = Financy::PAY_SYSTEM_ADMIN;
            $financy->save();

            //обновим баланс пользователя
            $user->updateCounters(['balance'=>$model->amount]);

            \Yii::$app->getSession()->setFlash('success', 'Успешно пополнили баланс пользователя.');

            return $this->redirect(['users']);
        } else {
            return $this->render('addbalance', [
                'model' => $model,
                'user'=>$user
            ]);
        }
    }

    /*
     * блокирование/разблокирование пользвоателя
     */
    public function actionChangeStatus($id){

        $user = $this->loadUser($id);

        $model = new ChangeStatusForm();

        $model->status = $user->status;

        $model->user_id = $user->id;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            $user->status = $model->status;

            $user->save();

            \Yii::$app->getSession()->setFlash('success', 'Успешно обновили статус пользователя.');

            return $this->redirect(['users']);
        }


        return $this->render('change_status',['model'=>$model,'user'=>$user]);
    }

    /*
     * смена пароля для выбранного юзера
     */
    public function actionChangePass($id){

        $user = $this->loadUser($id);

        $model = new ChangePasswordForm();
        $model->user_id = $user->id;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            $user->setPassword($model->new_password);

            $user->save();

            \Yii::$app->getSession()->setFlash('success', 'Успешно обновили пароль для пользователя.');

            return $this->redirect(['users']);
        }else{
            return $this->render('change_pass', [
                'model' => $model,
                'user'=>$user
            ]);
        }
    }

    /*
     *отображаем финансы по выбранному юзеру
     */
    public function actionFinancy($id){

        $model = $this->loadUser($id);

        $query = Financy::find()->where(['user_id'=>$id])->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('financy_user', [
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }

    /*
     * хистори его IP-адресов при авторизации
     */
    public function actionHistoryIp($id){


        $model = $this->loadUser($id);

        $query = AuthLog::find()->where(['user_id'=>$id])->orderBy('create_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('history_ip', [
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }

    protected function loadUser($id){
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * редактирование интервала подписки по юзеру
     */
    public function actionUpdateSubscribe($id){

        if (($model = UserSubscription::findOne($id)) !== null) {

            if ($model->load(\Yii::$app->request->post()) ) {

                $model->to = strtotime($model->to);

                $model->from = strtotime($model->from);

                \Yii::$app->db
                    ->createCommand('UPDATE user_subscription SET `to`='.$model->to.', `from`="'.$model->from.'" WHERE id='.$model->id)
                    ->execute();

                \Yii::$app->getSession()->setFlash('success', 'Успешно обновили дату подписок.');

                return $this->redirect(['users']);

            }else{
                return $this->render('update_subscribe_user', [
                    'model' => $model,
                ]);
            }


        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * удаляем пользователя
     */
    public function actionDelete($id){
        //самого себя не удаляем
        if(\Yii::$app->user->id==$id){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        //сперва удалим все данные по указанному юзеру

        $this->loadUser($id)->delete();

        //$this->redirect(Yii::$app->request->referrer);
        $this->goBack();
    }

    /*
     * подписка пользователя на указанную базу
     * $_POST[base_id] - id базы на котор. подписываем юзера
     * $_POST[user_id] - ID юзера, котор. оформляем подписку
     *
     * Хитрая логика:если была подписка на подсказки - она остается
            если была вечная - так и остается
            если была на полгода - продлевается до вечной

            если была подписка на бейс - при нажатии на про подписка меняется на про, при нажатии на бейс - ничего не меняется
            если была подписка на про - при нажатии на любую кнопку ничего не меняется
     */
    public function actionSubscribeUser()
    {

        if(\Yii::$app->request->post('user_id') && \Yii::$app->request->post('base_id'))
        {

            $subscribe_moab_base = UserSubscription::find()->where(['user_id'=>\Yii::$app->request->post('user_id'),'base_id'=>\Yii::$app->params['subscribe_moab_base_id']])->one();

            //если была подписка на бейс - при нажатии на про подписка меняется на про, при нажатии на бейс - ничего не меняется
            if(!empty($subscribe_moab_base) && \Yii::$app->request->post('base_id')==\Yii::$app->params['subscribe_moab_pro_id'])
            {

                $base_moab_pro = Base::findOne(\Yii::$app->params['subscribe_moab_pro_id']);

                \Yii::$app
                    ->db
                    ->createCommand()
                    ->update('user_subscription', [
                        'amount'=>$base_moab_pro->eternal_period_price,
                        'base_id'=>$base_moab_pro->id,
                        'from'=>time(),
                        'to'=>4133890800,
                    ], 'id='.$subscribe_moab_base->id)
                    ->execute();
            }else{

                //проверим есть ли у юзера уже опдписка по данной базе
                $subscribe = UserSubscription::find()->where(['user_id'=>\Yii::$app->request->post('user_id'),'base_id'=>\Yii::$app->request->post('base_id')])->one();

                //оформим подписку юзеру на базу
                $base = Base::findOne(\Yii::$app->request->post('base_id'));

                //нет подписки у юзера
                if(!$subscribe)
                {

                    if($base)
                    {
                        \Yii::$app
                            ->db
                            ->createCommand()
                            ->insert('user_subscription',[
                                'user_id'=>\Yii::$app->request->post('user_id'),
                                'base_id'=>\Yii::$app->request->post('base_id'),
                                'amount'=>$base->eternal_period_price,
                                'eternal_period'=>1,
                                'from'=>time(),
                                'to'=>4133890800,
                            ])
                            ->execute();

                        //добавим пользователю группу по умолчанию для его выборок
                        $category = new Category();
                        $category->user_id = \Yii::$app->request->post('user_id');
                        $category->title = 'Без группы';
                        $category->save();
                    }else{
                        throw new NotFoundHttpException('Not find base');
                    }
                }else{
                    //есть уже подписка у юзера по данной базе
                    \Yii::$app
                        ->db
                        ->createCommand()
                        ->update('user_subscription',[
                            'base_id'=>\Yii::$app->request->post('base_id'),
                            'amount'=>$base->eternal_period_price,
                            'eternal_period'=>1,
                            'from'=>time(),
                            'to'=>4133890800,
                        ],'id=',$subscribe->id)
                        ->execute();
                }
            }


            //подписка на яндекс-подсказки
            UserSubscription::deleteAll(['base_id'=>\Yii::$app->params['subsribe_moab_suggest'], 'user_id'=>\Yii::$app->request->post('user_id')]);

        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        \Yii::$app->getSession()->setFlash('success', 'Успешно оформили подписку пользователю');

        $this->goBack();
    }
}
