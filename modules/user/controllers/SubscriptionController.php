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
use app\modules\user\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class SubscriptionController extends UserMainController{


    public function behaviors()
    {
        return  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','rdp','subscribe'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /*
     * список подписок на которые юзер может подписаться+выделим на которые он подписан
     */
    public function actionIndex()
    {

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

        //получаем список подписанных баз юзера, по каждой подписке смотрим список исключений и по нему формируем список баз для скрытия
        $subsriptions = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->joinWith('base')->all();//->andWhere(['>','to',time()])

        //список исключений, какие базы скрываем для юзера
        $except_list = [];

        foreach($subsriptions as $subscribe){
            //актуальность подписки
            if($subscribe->to > time())
            {
                //может быть строка, проверим тип
                if(is_array($subscribe->base->hidebases)){
                    $except_list = ArrayHelper::merge($except_list, $subscribe->base->hidebases);
                }
            }
        }

        //Если пользователь уже был подписан на базу Яндекс.Подсказок ранее, то при подписке на любую из больших баз имеющуюся подписку на Яндекс.Подсказки
        // нужно аннулировать и из списка баз её скрывать, чтобы пользователь не имел возможности подписаться на неё повторно.

        $query = Base::find()->where(['enabled_user'=>1]);

        if(\app\modules\user\models\User::isSubscribeMoab())
        {
            //$query->andWhere('id!=:base_id',[':base_id'=>\Yii::$app->params['subsribe_moab_suggest']]);
        }



        $dataProvider = new ActiveDataProvider([
            'query' => $query/*Base::find()->where(['enabled_user'=>1])*/,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        //список всех подписок юзера
       // $subsriptions = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->all();

        return $this->render('index', ['dataProvider'=>$dataProvider, 'subsriptions'=>$subsriptions, 'user'=>User::findOne(\Yii::$app->user->id),'except_list'=>$except_list]);
    }

    /*
     * оформление подписки пользователя
     */
    public function actionSubscribe($id)
    {

        if(\Yii::$app->request->isAjax){

            //выясним это новая подписка или продление уже существующей
            $model  = UserSubscription::findOne(['user_id'=>\Yii::$app->user->id, 'base_id'=>$id]);

            //продление подписки по выбранной базе
            if ($model  !== null) {

                $subs_old = $this->loadSubscribe($model->id);

                //первая подписка по базе у юзера
                if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

                    //обновим период подписки пользователя по выбранной базе
                    //если конечная дата подписки больше текущей, то добавим к последней дате выбарнное кол-во месяцев
                    if($model->to>time()){
                        $model->to = $model->calculateDateTo($model->to);
                    }else{
                        //дата завершения подписки прошла уже, значит установим
                        $model->from = time();
                        $model->to = $model->calculateDateTo($model->from);
                    }

                    $model->amount = $model->amount;//обновим стоимость продолжения подписок

                    $model->_periodName = $model->periodName;//текстовое описание периода подписок

                    $model->save(false);//обновим лишь финальную дату подписки

                    \Yii::$app->getSession()->setFlash('success', 'Успешно продлили подписку.');

                    return $this->redirect(['/subscription']);
                }
                return $this->renderAjax('_form', ['model'=>$model->base, 'subs'=>$model]);
            }else{
                $model = new UserSubscription();
                $model->user_id = \Yii::$app->user->id;
                $model->base_id = $id;

                //первая подписка по базе у юзера
                if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

                    if($model->save()){
                        \Yii::$app->getSession()->setFlash('success', 'Успешно оформили подписку.');
                    }else{

                        \Yii::$app->getSession()->setFlash('error', print_r($model->errors, true));
                    }

                    return $this->redirect(['/subscription']);
                }

                return $this->renderAjax('_form', ['model'=>$model->base, 'subs'=>$model]);
            }
        }else{
            throw new BadRequestHttpException('Не верный тип запроса', 400);
        }
    }


    private function loadSubscribe($id)
    {
        if (($model = UserSubscription::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * скачивание файла, в котором формируем динамически данные для RDP - подключения
     */
    public function actionRdp(){

        $user = User::findOne(['id'=>\Yii::$app->user->id]);


        //есть подписки у юзера+ есть аккаунт для отображения->формируем динамически файл
        if(($user->subscription) && ($user->accessServer!=='Нет')){

            $file = 'screen mode id:i:1
use multimon:i:0
desktopwidth:i:1280
desktopheight:i:768
session bpp:i:32
winposstr:s:0,1,8,31,1304,838
compression:i:1
keyboardhook:i:0
audiocapturemode:i:1
videoplaybackmode:i:1
connection type:i:6
networkautodetect:i:0
bandwidthautodetect:i:1
displayconnectionbar:i:1
enableworkspacereconnect:i:0
disable wallpaper:i:1
allow font smoothing:i:0
allow desktop composition:i:0
disable full window drag:i:1
disable menu anims:i:1
disable themes:i:1
disable cursor setting:i:0
bitmapcachepersistenable:i:1
full address:s:'.$user->accessServer.'
audiomode:i:0
redirectprinters:i:0
redirectcomports:i:0
redirectsmartcards:i:0
redirectclipboard:i:1
redirectposdevices:i:0
drivestoredirect:s:C:\
autoreconnection enabled:i:1
authentication level:i:0
prompt for credentials:i:0
negotiate security layer:i:1
remoteapplicationmode:i:0
alternate shell:s:
shell working directory:s:
gatewayhostname:s:
gatewayusagemethod:i:4
gatewaycredentialssource:i:4
gatewayprofileusagemethod:i:0
promptcredentialonce:i:0
use redirection server name:i:0
rdgiskdcproxy:i:0
kdcproxyname:s:
username:s:'.$user->accessLogin.'
disable ctrl+alt+del:i:1
administrative session:i:0';


            return \Yii::$app->response->sendContentAsFile($file,trim($user->accessServer).".rdp")->send();

        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}