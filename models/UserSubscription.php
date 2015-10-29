<?php

namespace app\models;

use app\modules\user\models\User;
use Yii;
use app\models\Base;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_subscription".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $base_id
 * @property integer $period
 *
 * @property Base $base
 * @property User $user
 */
class UserSubscription extends \yii\db\ActiveRecord
{

    const PERIOD_ONE_MONTH = 'one_month';//период 1 месяц //''
    const PERIOD_THREE_MONTH = 'three_month';// период 3 месяца ''
    const PERIOD_SIX_MONTH = 'six_month';// период 6 месяцев  ''
    const PERIOD_TWELFTH_MONTH = 'twelfth_month';// период 12 месяцев  ''
    const PERIOD_ETERNAL = 'eternal_period';// период - на всегда  ''


    public $amountSubscribe;//итоговая сумма пополнения, при продлении подписки

    public $_periodName = [];

    /*
     * список периодов в ввиде массива
     */
    static function getPeriodList(){
        return [
            self::PERIOD_ONE_MONTH=>'Период 1 месяц',
            self::PERIOD_THREE_MONTH=>'Период 3 месяца',
            self::PERIOD_SIX_MONTH=>'Период 6 месяцев',
            self::PERIOD_TWELFTH_MONTH=>'Период 1 год',
            self::PERIOD_ETERNAL=>'Период навсегда'
        ];
    }

    public function getPeriodName(){

        if(!empty($this->_periodName)){
            return $this->$_periodName;
        }

        $listPeriod = self::getPeriodList();

        foreach($this->attributes as $attribute=>$value){
            if($this->{$attribute}==1 && !empty($listPeriod[$attribute])){
                $this->_periodName[] = $listPeriod[$attribute];
            }
        }

        return $this->_periodName;
    }

    public function getFinalTo(){

        $period = [];

        $list = self::getPhpPeriodList();

        foreach($this->attributes as $attribute=>$value){
            if($this->{$attribute}==1 && !empty($list[$attribute])){
                $period[] = $list[$attribute];
            }
        }

        $this->to = $this->from;

        foreach($period as $add){
            if(empty($add)){
                $this->to = 4133890800;
            }else{
                $this->to = strtotime( date('d-m-Y H:i:s', $this->to) . $add);
            }

        }

        return $period;
    }

    static function getPhpPeriodList(){
        return [
            self::PERIOD_ONE_MONTH=>'+1 month',
            self::PERIOD_THREE_MONTH=>'+3 month',
            self::PERIOD_SIX_MONTH=>'+6 month',
            self::PERIOD_TWELFTH_MONTH=>'+12 month',
            self::PERIOD_ETERNAL=>'+ 100 years' ,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_id'], 'required'],
            ['base_id','validateInterval'],
            ['base_id','userCanSubcribe'],
            [['user_id', 'amount','base_id','one_month','three_month','six_month','twelfth_month','eternal_period', 'share'], 'integer'],
            ['user_id', 'default', 'value'=>\Yii::$app->user->id],
            ['desc', 'string', 'max' => 5255],

            ['from', 'default', 'value'=>time()],


            ['to', 'default', 'value' => function ($value) {

                //подсчитаем итоговую дату завершения подписки, с учётом выбранных месячев
                $to = $this->calculateDateTo($this->from);

                $this->to = $to;

                return $this->to;
            }],
        ];
    }

    /*
     * подсчёт конечной даты подписки
     * $startDate - дата по которой ориентируемся при добавлении выбранного кол-ва месяцев подписки
     */
    public function calculateDateTo($startDate){

        $period = [];

        $list = self::getPhpPeriodList();

        foreach($this->attributes as $attribute=>$value){
            if($this->{$attribute}==1 && !empty($list[$attribute])){
                $period[] = $list[$attribute];
            }
        }

        $to = $startDate;

        foreach($period as $add){
            if(empty($add)){
                $to = 4133890800; break;
            }else{
                $to = strtotime( date('d-m-Y H:i:s', $to) . $add);
            }

        }

        return $to;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'base_id' => 'База',
            'from'=>'Начало',
            'to'=>'Конец',
            'desc'=>'Интервал подписки',
            'one_month'=>'1 мес.',
            'three_month'=>'3 мес.',
            'six_month'=>'6 мес.',
            'twelfth_month'=>'12 мес.',
            'eternal_period'=>'Вечная',
            'amount'=>'Сумма подписки',
            'share'=>'Акционная подписка'
        ];
    }

    /*
     * получаем описание интервала подписки на базу
     */
    public function getDesc()
    {
        if($this->to){
            if($this->to>=4133890800){
                return 'Начало: <strong>'.date('d-m-Y H:i:s', $this->from).'</strong><br>'.' Окончание: <strong>не ограничено </strong>';
            }else{
                return 'Начало: <strong>'.date('d-m-Y H:i:s', $this->from).'</strong><br>'.' Окончание: <strong>'.date('d-m-Y H:i:s', $this->to).'</strong>';
            }
        }

        return '';
    }

    /*
     * проверям подписку на актуальность
     */
    public function isExpired(){
        if($this->to){
            if($this->from<=time() && $this->to>time()){
                return true;
            }
        }

        return false;
    }

    /*
     *по текущему юзеру проверяем если у него вообще актуальные подписки
     * НЕ по веб-вервисям подписок баз
     * для скрытия различных элементов внутри кабинета, которые ранее отображались юзеру, когда он получал подписку
     *
     */
    static function userHaveActualSubscribe(){

        //юзер-авторизирован
        if(!Yii::$app->user->isGuest){

            return Yii::$app
                    ->db
                    ->createCommand(
                        'SELECT user_subscription.id
                        FROM user_subscription
                        LEFT JOIN base on user_subscription.base_id=base.id
                        WHERE user_subscription.user_id=:user_id
                          AND user_subscription.from<:time AND user_subscription.to>:time
                          AND ISEMPTY(base.cabinet_link)
                        LIMIT 1'
                    )
                    ->cache(60)
                    ->bindValues([':user_id'=>Yii::$app->user->id,':time'=>time()])
                    ->queryScalar();
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(Base::className(), ['id' => 'base_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /*
     * проверим, чтобы обязательно был выбран хотя бы 1 интервал времени подписки
     */
    public function validateInterval($attribute, $params){
        if(!$this->hasErrors()){
            if(($this->one_month + $this->three_month + $this->six_month + $this->twelfth_month + $this->eternal_period)==0){
                $this->addError('desc', 'Не выбран ни один период подписки');
            }
            if(empty($this->user_id)){
                $this->user_id = Yii::$app->user->id;
            }
        }
    }

    /*
     * валидируем возможность подписки юзеров на базу
     */
    public function userCanSubcribe($attribute, $params){
        if(!$this->hasErrors()){

            if(empty($this->user_id)){
                $this->user_id = \Yii::$app->user->id;
            }
            //сравним баланс пользователя с суммой по выбранному интервалу
            $amountSubcribe = $this->getPriceSubcribe();//цена за подписку

            //цена за подписку превышает баланс юзера
            if($amountSubcribe > $this->user->balance){
                $this->addError('desc', 'На   балансе   недостаточно   средств   для   подписки');
            }else{
                $this->amount = $amountSubcribe;
                $this->amountSubscribe = $amountSubcribe;
            }
        }
    }

    /*
     * узнаем цену по выбранному интервалу пользователя,
     * на выбранную базу
     */
    public function getPriceSubcribe(){

        //общая цена по всей выбранной подписке, может быть несколько интервалов
        $total_price = 0;

        foreach($this->attributes as $attribute=>$value){
            //если есть совпадение по аттрибуту и он выбран галочкой
            if($this->base->hasAttribute($attribute.'_price') && $this->$attribute==1){
                $total_price = $total_price + $this->base->{$attribute.'_price'};
            }
        }

        return $total_price;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        //запишим списание с баланса юзера в фин. операции
        $financy = new Financy();
        $financy->user_id = $this->user_id;
        $financy->amount = $this->amount;
        $financy->balance_after = ($this->user->balance - $this->amount);
        $financy->type_operation = Financy::TYPE_OPERATION_MINUS;
        $financy->status = Financy::STATUS_PAID;
        $financy->desc = 'Подписка на базу '.$this->base->title.' '.implode(', ', empty($this->_periodName)?$this->periodName:$this->_periodName);
        $financy->pay_system = Financy::PAY_SYSTEM_BILL;
        if($financy->validate())
        {
            $financy->save();
        }else{
            print_r($financy->errors);
        }

        //списываем с баланса юзера сумму подписки
        $this->user->updateCounters(['balance'=>-$this->amount]);

        //при первой подписке - пользователю выдаём доступ к RDP сервера
        //первая подписка юзера
        if(UserAccess::find()->where(['user_id'=> $this->user_id])->count()==0)
        {
            //выдадим ему доступ к удалённому подключению
            $access = Access::find()->busy()->one();

            $userAccess = new UserAccess();
            $userAccess->user_id =  $this->user_id;
            $userAccess->access_id = $access['id'];
            $userAccess->save();

            //обновим статус у аккаунта RDP
            Access::updateAll(['busy'=>Access::STATUS_BUSY], ['id'=>$access['id']]);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /*
     * подсчитаем сумму денег которую может вернуть/получить пользователь на тек день
     * расчитываем  - вначале сумму за день подсчитываем, а после умножаем сумму за день на кол-во оставшихся дней активной подписки для юзера
     * $subscribe - массив информации о подписке юзера
     */
    public static function calculateBackSummRecovery($subscribe)
    {
        //вечная подписка
        if($subscribe['eternal_period']==1)
        {
            //определяем сумму вечной подписки по указанной базе в подписке
            return Yii::$app->db->createCommand('SELECT eternal_period_price FROM base WHERE id=:id',[':id'=>$subscribe['base_id']])->queryScalar();
        }

        //проверим, чтобы подписка была актуальна на тек. день
        if($subscribe['to']>time())
        {
            //дата подписки=тек. дате
            if(date('Y-m-d', time()) == date('Y-m-d', $subscribe['from']))
            {
                return $subscribe['amount'];
            }

            //подсчитаем кол-во дней, оствшейся подписки
            $dStart = new \DateTime(date('Y-m-d', time()));

            $dEnd  = new \DateTime(date('Y-m-d', $subscribe['to']));

            $dDiff = $dStart->diff($dEnd);
            //echo $dDiff->format('%R'); // use for point out relation: smaller/greater
            //осталось дней до конца подписки
            $left_days_subscribe = $dDiff->days;

            //подсчитаем стоимость одного дня подписки
            $dStart_ = new \DateTime(date('Y-m-d', $subscribe['from']));
            $dEnd_  = new \DateTime(date('Y-m-d', $subscribe['to']));
            $dDiff_total = $dStart_->diff($dEnd_);

            $price_one_day = round($subscribe['amount']/$dDiff_total->days);

            return round($left_days_subscribe*$price_one_day);
        }

    }

    /*
     * фун-я возврата баланса по подписке
     * 1) работает только для активных подписок+ не акционных
     *2)доступна только админам
     * ЛОгика:
     * подсчитываем сумму возврата юзеру за ранее купленную базу, перед покупкой/докупкой новой БД
     */
    static function returnBalanceBySubscribe(UserSubscription $subscribe)
    {
        //исключаем акционную подписку
       if($subscribe->share==0)
       {
           //подсчитаем сумму возврата денег по подписке юзера
           $back_sum_return = self::calculateBackSummRecovery(ArrayHelper::toArray($subscribe));

           //если сумма больше нуля - отменяем подписку и возвращаем сумму на баланс пользователя
           if($back_sum_return > 0 )
           {

               if(date('d-m-Y',$subscribe->to) == date('d-m-Y'))
               {
                   $to = ($subscribe->from + 10);
               }else{
                   $to = strtotime('-1 day');
               }

               //срок действия подписки установили на вчера - подписка не активна
               Yii::$app->db->createCommand('UPDATE user_subscription SET `to`=:to, eternal_period=0 WHERE id=:id',[':id'=>$subscribe->id, ':to'=>$to])->execute();

               $user = $subscribe->user;

               //пополняем баланс юзера на сумму - Дельты
               //запишим операцию пополнения баланса фин. операции
               $financy = new Financy();
               $financy->user_id = $subscribe->user_id;
               $financy->amount = $back_sum_return;//пересчитанная дельта
               $financy->balance_after = ($user->balance + $back_sum_return);
               $financy->type_operation = Financy::TYPE_OPERATION_PLUS;
               $financy->status = Financy::STATUS_PAID;
               $financy->desc = 'Возврат неиспользованного баланса';
               $financy->pay_system = Financy::PAY_SYSTEM_ADMIN;
               if($financy->validate())
               {
                   $financy->save();
               }else{
                   print_r($financy->errors); die();
               }

               //пополняем баланс юзера
               $user->updateCounters(['balance'=>$back_sum_return]);
           }
       }
    }

}
