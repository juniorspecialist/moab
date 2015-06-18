<?php

namespace app\models;

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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'financy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'balance_after', 'amount', 'type_operation', 'create_ad'], 'required'],
            [['id', 'user_id', 'balance_after', 'amount', 'type_operation', 'create_ad'], 'integer']
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
            'type_operation' => 'Дата',
            'create_ad' => 'Дата операции',
        ];
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
