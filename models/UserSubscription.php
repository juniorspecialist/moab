<?php

namespace app\models;

use app\modules\user\models\User;
use Yii;
use app\models\Base;

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


    /*
     * список периодов в ввиде массива
     */
    static function getPeriodList(){
        return [
            self::PERIOD_ONE_MONTH=>'Период 1 месяц',
            self::PERIOD_THREE_MONTH=>'Период 3 месяца',
            self::PERIOD_SIX_MONTH=>'Период 6 месяцев',
            self::PERIOD_TWELFTH_MONTH=>'Период 1 год',
        ];
    }

    public function getPeriodName(){

        $period = [];

        $listPeriod = self::getPeriodList();

        foreach($this->attributes as $attribute=>$value){
            if($this->{$attribute}==1 && !empty($listPeriod[$attribute])){
                $period[] = $listPeriod[$attribute];
            }
        }

        return $period;
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
            $this->to = strtotime( date('d-m-Y H:i:s', $this->to) . $add);
        }

        return $period;
    }

    static function getPhpPeriodList(){
        return [
            self::PERIOD_ONE_MONTH=>'+1 month',
            self::PERIOD_THREE_MONTH=>'+3 month',
            self::PERIOD_SIX_MONTH=>'+6 month',
            self::PERIOD_TWELFTH_MONTH=>'+12 month',
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
            [['user_id', 'amount','base_id','one_month','three_month','six_month','twelfth_month'], 'integer'],
            ['user_id', 'default', 'value'=>\Yii::$app->user->id],
            ['desc', 'string', 'max' => 5255],

            ['from', 'default', 'value'=>time()],

            ['to', 'default', 'value' => function ($value) {
                //
                $period = [];

                $list = self::getPhpPeriodList();

                foreach($this->attributes as $attribute=>$value){
                    if($this->{$attribute}==1 && !empty($list[$attribute])){
                        $period[] = $list[$attribute];
                    }
                }

                $this->to = $this->from;

                foreach($period as $add){
                    $this->to = strtotime( date('d-m-Y H:i:s', $this->to) . $add);
                }

                return $this->to;
            }],
        ];
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
            'amount'=>'Сумма подписки',
        ];
    }

    /*
     * получаем описание интервала подписки на базу
     */
    public function getDesc()
    {
        if($this->to){
            return 'Начало:'.date('Y-m-d H:i:s', $this->from).'<br>'.' Конец:'.date('Y-m-d H:i:s', $this->to);
        }

        return '';
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
        //if(!$this->hasErrors()){
            if(($this->one_month + $this->three_month + $this->six_month + $this->twelfth_month)==0){
                $this->addError('desc', 'Не выбран ни один период подписки');
            }
        if(empty($this->user_id)){
            $this->user_id = Yii::$app->user->id;
        }
        //}
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
                $this->addError('desc', 'Стоимость подписки превышает сумму вашего баланса');
            }else{
                $this->amount = $amountSubcribe;
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
        $financy->desc = 'Подписка на базу '.$this->base->title.' '.implode(',', $this->periodName);
        $financy->pay_system = Financy::PAY_SYSTEM_BILL;
        if($financy->validate()){
            $financy->save();
        }else{
            print_r($financy->errors);
        }

        //списываем с баланса юзера сумму подписки
        $this->user->updateCounters(['balance'=>-$this->amount]);

        //при первой подписке - пользователю выдаём доступ к RDP сервера
        //первая подписка юзера
        if(UserSubscription::find()->where(['user_id'=>\Yii::$app->user->id])->count()==1){
            //выдадим ему доступ к удалённому подключению
            $access = Access::find()->busy()->one();

            $userAccess = new UserAccess();
            $userAccess->user_id = \Yii::$app->user->id;
            $userAccess->access_id = $access['id'];
            $userAccess->save();

            //обновим статус у аккаунта RDP
            Access::updateAll(['busy'=>Access::STATUS_BUSY], ['id'=>$access['id']]);
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
