<?php

namespace app\models;

use app\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "access".
 *
 * @property integer $id
 * @property integer $busy
 * @property string $login
 * @property string $pass
 * @property string $server
 */
class Access extends \yii\db\ActiveRecord
{

    public $upload;


    const STATUS_FREE = 1;//свободен
    const STATUS_BUSY = 2;//занят

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'login', 'pass', 'server'], 'required', 'on'=>'create'],
            [[ 'busy'], 'integer'],
            [[ 'busy'], 'default', 'value'=>self::STATUS_FREE],
            [['login', 'pass', 'server'], 'string', 'max' => 60],
            [['upload'], 'required', 'on'=>'upload'],
            ['login', 'unique', 'targetAttribute' => ['login', 'server'],'on'=>'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'busy' => 'Используется',
            'login' => 'Логин',
            'pass' => 'Пароль',
            'server' => 'Сервер',
            'upload'=>'Данные для загрузки',
        ];
    }

    /**
     * @inheritdoc
     * @return AccessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccessQuery(get_called_class());
    }


    /*
        * @return \yii\db\ActiveQuery
        * получаем подвязанного юзера к доступу
        */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->viaTable(UserAccess::tableName(), ['access_id'=>'id']);
    }

    public function getUserEmail(){
        if($this->busy==self::STATUS_BUSY){
            return $this->user ? $this->user->email : '';
        }else{
            return '';
        }
    }

}
