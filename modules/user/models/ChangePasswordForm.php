<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.06.15
 * Time: 22:56
 */

namespace app\modules\user\models;


use yii\base\Model;
use yii\web\NotFoundHttpException;

class ChangePasswordForm extends Model{

    public $new_password;
    public $user_id;

    public function rules()
    {
        return [
            [['new_password', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['new_password'], 'string', 'min' => 6]
        ];
    }

    public function attributeLabels()
    {
        return [
            'new_password' => 'Новый пароль для пользователя',
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