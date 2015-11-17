<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "action".
 *
 * @property integer $id
 * @property integer $period_from
 * @property integer $period_to
 * @property string $promo
 * @property integer $base_id
 * @property integer $eternal_period
 *
 * @property Base $base
 * @property ActionUser[] $actionUsers
 */
class Action extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period_from', 'period_to', 'promo', 'base_id', 'subscribe_period'], 'required'],
            [['base_id','disposable','used'], 'integer'],
            ['promo', 'unique'],
            [['promo','subscribe_period'], 'string', 'max' => 50],
            // normalize "date" input
            [['period_from','period_to'], 'filter', 'filter' => function ($value) {
                return strtotime($value);
            }],

        ];
    }


    public function afterFind()
    {

        parent::afterFind();

        //преобразование дат к нужному формату
        $this->period_from = date('d.m.Y H:i',$this->period_from);

        $this->period_to = date('d.m.Y H:i',$this->period_to);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period_from' => 'Начало',
            'period_to' => 'Завершение',
            'promo' => 'Промо код',
            'base_id' => 'База',
            'subscribe_period' => 'Подписка на срок',
            'disposable'=>'Одноразовая',
            'used'=>'Использована'
        ];
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
    public function getActionUsers()
    {
        return $this->hasMany(ActionUser::className(), ['action_id' => 'id']);
    }
}
