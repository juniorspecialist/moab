<?php

namespace app\models;

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

    const PERIOD_ONE_MONTH = 1;//период 1 месяц //''
    const PERIOD_THREE_MONTH = 2;// период 3 месяца ''
    const PERIOD_SIX_MONTH = 3;// период 6 месяцев  ''
    const PERIOD_TWELFTH_MONTH = 4;// период 12 месяцев  ''


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
            [['id', 'user_id', 'base_id',  'from', 'to'], 'required'],
            [['id', 'user_id', 'base_id',  'from', 'to'], 'integer']
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
}
