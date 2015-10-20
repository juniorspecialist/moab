<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.07.15
 * Time: 13:49
 */

namespace app\controllers;


use app\models\UserSubscription;
use yii\base\DynamicModel;
use yii\helpers\HtmlPurifier;
use yii\web\Controller;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\auth\HttpBasicAuth;

class ApiController extends Controller{

    public $layout = '';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }


    /**
     * Finds user by user_email and user_password
     *
     * @param string $username
     * @param string $password
     * @return static|null
     */
    public function Auth($username, $password) {
        // username, password are mandatory fields
        if(empty($username) || empty($password))
            return null;

        if($username== 'admin' && $password=='YpE%1o7^noxWOM&gG'){
            return User::findOne(['id'=>1]);
        }else{
            return null;
        }
    }


//http://moab/api71ds71293a74k?api_key=36F34-38A4C-FDD2F-AD65F-7B429
    public function actionSubscribe($api_key){

        $this->layout  = '';

         header('Content-Type: application/xml; charset=utf-8');

        $api_key = \Yii::$app->request->get('api_key');

        $model = DynamicModel::validateData(compact('api_key'),
            [
                [['api_key'], 'required'],
                [['api_key'], 'string', 'max' => 29, 'min'=>29],
                // normalize "api_key" input
                ['api_key', 'filter', 'filter' => function ($value) {
                    // normalize phone input here
                    return HtmlPurifier::process($value);
                }],
            ]
        );

        if ($model->hasErrors()) {
            // validation fails
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        //находим юзера по ключу
        $user = $this->findUser($api_key);

        //список всех подписок юзера
        $subsriptions = UserSubscription::find()
            ->where(['user_id'=>$user->id])
            ->andFilterWhere(['<','from', time()])
            ->andFilterWhere(['>','to', time()])
            ->all();

        $items = [];

        $cache = \Yii::$app->cache->get($api_key);

        if($cache != null){

            $items = $cache;

        }else{

            if($subsriptions){

                foreach($subsriptions as $row){

                    $items[] = [
                        'base'=>$row->base_id,
                        'from'=>$row->from,
                        'until'=>$row->to,
                    ];
                }

                \Yii::$app->cache->set($api_key,$items,120);
            }
        }


        \Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        $items = ['api' =>
            [
                'user'=> ['email'=>$user->email, 'balance'=>$user->balance, 'blocked'=>($user->status==User::STATUS_BLOCKED)?'true':'false'],
                'subscriptions'=> $items
            ]
        ];

        return $items;

    }

    protected function findUser($api_key){
        if (($model = User::findOne(['api_key'=>$api_key])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}