<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.09.15
 * Time: 16:25
 */

namespace app\modules\user\models;


use app\models\Action;
use app\models\ActionUser;
use app\models\Financy;
use app\models\UserSubscription;
use yii\base\Model;
use yii\helpers\BaseHtmlPurifier;

class PromoUser extends Model{

    public $promo;

    public $action;//AR найденной акции по промо - коду

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['promo', 'required'],

            ['promo', 'filter', 'filter' => 'trim'],

            ['promo', 'filter', 'filter' => function($value){
                return BaseHtmlPurifier::process($value);
            }],

//            ['promo', 'exist',
//                'targetClass' => 'app\modules\user\models\PromoUser',
//                'message' => 'Не найдена акция по вашему промо коду.'
//            ],
            //возможно у юзера уже есть подписка на базу, по его промокоду
            ['promo','validatePromo'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promo' => 'Промо код',
        ];
    }

    public function validatePromo()
    {
        if(!$this->hasErrors())
        {
            $this->action = Action::findOne(['promo'=>$this->promo]);

            if($this->action)
            {

                //сравним период действия акции
                if(strtotime($this->action->period_from)<time() && strtotime($this->action->period_to)>time())
                {
                    //проверим нет ли у юзера вообще любой подписки по тек. бд
                    $sunbscribe = UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id, 'base_id'=>$this->action->base_id])->one();

                    if($sunbscribe){
                        $this->addError('promo','Извините, но у вас уже есть подписка на базу '.$this->action->base->title);
                    }
                }else{
                    $this->addError('promo','Извините, промо код по вашей акции не активен');
                }
            }else{
                $this->addError('promo','Извините, по вашему промо коду не найдена акция');
            }
        }
    }

    /*
     * проверили все параметры и можем активировать подписку юзеру по промо-коду
     * При активации выданного промокода его баланс будет пополняться на сумму, которую данный промокод предусматривает и ему будет автоматически активироваться та подписка,
     *  которая по этому промокоду должна быть активирована, баланс, соответственно,будет списываться
     */
    public function activate()
    {
        if(!$this->hasErrors())
        {

            $user = User::findOne(\Yii::$app->user->id);

            //запишим операцию пополнения в общий лог. фин. операций
            $financy = new Financy();
            $financy->user_id = \Yii::$app->user->id;
            $financy->amount = $this->action->base->{$this->action->subscribe_period.'_price'};
            $financy->balance_after = $user->balance + $this->action->base->{$this->action->subscribe_period.'_price'};
            $financy->type_operation = Financy::TYPE_OPERATION_PLUS;
            $financy->status = Financy::STATUS_PAID;
            $financy->desc = 'Пополнение от администрации сайта';
            $financy->pay_system = Financy::PAY_SYSTEM_ADMIN;
            $financy->save();

            //обновим баланс юзера
            $user->updateCounters(['balance'=>$this->action->base->{$this->action->subscribe_period.'_price'}]);

            $php_list = UserSubscription::getPhpPeriodList();

            //эмулируем покупку подписки на базу-1
            $userSubscribe = new UserSubscription();
            $userSubscribe->user_id = $user->id;
            $userSubscribe->base_id = $this->action->base_id;
            $userSubscribe->amount = $this->action->base->{$this->action->subscribe_period.'_price'};
            $userSubscribe->from = time();
            $userSubscribe->to = strtotime($php_list[$this->action->subscribe_period]);
            $userSubscribe->share = 1;

            if($this->action->subscribe_period==UserSubscription::PERIOD_ETERNAL)
            {
                $userSubscribe->eternal_period = 1;
            }

            $userSubscribe->save(false);

            //запишим активацию акционной подписки по промо-коду
            $promo_user = new ActionUser();
            $promo_user->action_id = $this->action->id;
            $promo_user->user_id = \Yii::$app->user->id;
            $promo_user->save();
        }

    }
}