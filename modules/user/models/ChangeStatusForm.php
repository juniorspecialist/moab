<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.06.15
 * Time: 23:17
 */

namespace app\modules\user\models;


use yii\base\Model;
use yii\web\NotFoundHttpException;

class ChangeStatusForm extends Model{

    public $status;

    public $user_id;

    public function rules()
    {
        return [
            [['status', 'user_id'], 'required'],
            [['status','user_id'], 'integer'],
            //[['new_password'], 'string', 'min' => 6]
        ];
    }

    public function attributeLabels()
    {
        return [
            'status' => 'Статус пользователя',
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