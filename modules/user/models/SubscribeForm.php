<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.06.15
 * Time: 13:08
 */

namespace app\modules\user\models;


use yii\base\Model;

class SubscribeForm extends Model{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'base_id' => 'База',
            'desc'=>'Интервал подписки',
            'one_month'=>'1 мес.',
            'three_month'=>'3 мес.',
            'six_month'=>'6 мес.',
            'twelfth_month'=>'12 мес.',
            'amount'=>'Сумма подписки',
        ];
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
            ['from', 'default', 'value'=>time()],
        ];
   }
}