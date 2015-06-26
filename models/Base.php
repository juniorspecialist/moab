<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base".
 *
 * @property integer $id
 * @property string $title
 *
 * @property UserSubscription[] $userSubscriptions
 */
class Base extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'one_month_price','three_month_price','six_month_price','twelfth_month_price'], 'required'],
            [['one_month_price','three_month_price','six_month_price','twelfth_month_price'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['one_month_user_info','three_month_user_info','six_month_user_info','twelfth_month_user_info'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'one_month_price'=>'Цена(1 месяц)',
            'three_month_price'=>'Цена(3 месяца)',
            'six_month_price'=>'Цена(6 месяцев)',
            'twelfth_month_price'=>'Цена(12 месяцев)',

            'one_month_user_info'=>'Пользовательская информация(1 месяц)',
            'three_month_user_info'=>'Пользовательская информация(3 месяца)',
            'six_month_user_info'=>'Пользовательская информация(6 месяцев)',
            'twelfth_month_user_info'=>'Пользовательская информация(12 месяцев)',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSubscription()
    {
        return $this->hasMany(UserSubscription::className(), ['base_id' => 'id']);
    }
}
