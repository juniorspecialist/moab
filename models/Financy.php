<?php

namespace app\models;

use app\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "financy".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $balance_after
 * @property integer $amount
 * @property integer $type_operation
 * @property integer $create_ad
 *
 * @property User $user
 */
class Financy extends \yii\db\ActiveRecord
{

    const TYPE_OPERATION_PLUS = 1;
    const TYPE_OPERATION_MINUS = 2;


    const STATUS_PAID = 1;//статус - оплачено
    const STATUS_NOT_PAID = 2;//статус - НЕ оплачено

    const PAY_SYSTEM_ROBOKASSA = 1;//пополнение через робокассу
    const PAY_SYSTEM_WEBMONEY = 2;// пополнение через вэб-мани
    const PAY_SYSTEM_ADMIN = 3;// пополнение через админку, -админ пополнил баланс юзера
    const PAY_SYSTEM_BILL = 4;// покупка подписки

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financy';
    }

    static function getPaySystemList(){
        return [
            self::PAY_SYSTEM_ADMIN =>'Пополнение админа',
            self::PAY_SYSTEM_ROBOKASSA=>'Robokassa',
            self::PAY_SYSTEM_WEBMONEY=>'Web money',
            self::PAY_SYSTEM_BILL=>'Подкупка подписки',
        ];
    }

    public function getPaySystem(){
        $list = self::getPaySystemList();
        return $list[$this->pay_system];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance_after', 'amount', 'type_operation', 'desc','pay_system'], 'required'],
            [['status', 'user_id', 'balance_after', 'amount', 'type_operation', 'create_at','pay_system'], 'integer'],
            ['create_at', 'default', 'value'=>time()],
            ['status', 'default', 'value'=>self::STATUS_NOT_PAID],
            ['user_id', 'default', 'value'=>\Yii::$app->user->id],
            ['desc', 'string', 'max' => 600],
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
            'balance_after' => 'Баланс',
            'amount' => 'Сумма',
            'paySystem'=>'Система оплаты',
            'create_at' => 'Дата операции',
            'desc'=>'Описание',
            'status'=>'Статус',
            'type_operation'=>'Система оплаты',
        ];
    }

    static function getStatusList(){
        return [
            self::STATUS_NOT_PAID=>'Не оплачен',
            self::STATUS_PAID=>'Оплачен',
        ];
    }

    /*
     * получаем текстовое представление статуса заявки на пополнение
     */
    public function getStatusName()
    {
        $list = self::getStatusList();

        return $list[$this->status];
    }

    static function getListOperation()
    {
        return [
            self::TYPE_OPERATION_MINUS => 'Списание',
            self::TYPE_OPERATION_PLUS => 'Пополнение'
        ];
    }

    /*
     * тип операции
     */
    public function getTypeOperation()
    {
        $list = Financy::getListOperation();

        return $list[$this->type_operation];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return FinancyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FinancyQuery(get_called_class());
    }
}
