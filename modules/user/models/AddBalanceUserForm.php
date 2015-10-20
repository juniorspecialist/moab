<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.06.15
 * Time: 21:03
 */

namespace app\modules\user\models;


use yii\base\Model;
use yii\web\NotFoundHttpException;

class AddBalanceUserForm extends Model{

    public $amount;
    public $user_id;

    public function rules()
    {
        return [
            [['amount', 'user_id'], 'required'],
            [['amount'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Сумма',
            'user_id' => 'Пользователь',
        ];
    }

    protected function loadUser($id){
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}