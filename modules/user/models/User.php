<?php

namespace app\modules\user\models;

use app\models\Access;
use app\models\AuthLog;
use app\models\AuthLogQuery;
use app\models\Financy;
use app\models\UserAccess;
use app\models\UserSubscription;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\debug\components\search\matchers\Base;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $username
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;

    const EVENT_AFTER_LOGIN  = 'afterLogin';

    public $admin;
    //public $access;



    public function getStatusName()
    {
        $statuses = self::getStatusesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }


    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_WAIT => 'Ожидает подтверждения',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    public function getSubc(){
        return $this->hasMany(\app\models\Base::className(), ['id' => 'base_id'])->via('Usersubscription');
    }


    public function getUsersubscription(){
        return $this->hasMany(UserSubscription::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * подписки юзера по выбранным базам и выбранным периодам
     */
    public function getSubscription()
    {
        return $this->hasMany(\app\models\Base::className(), ['id' => 'base_id'])->viaTable(UserSubscription::tableName(), ['user_id' => 'id']);
    }
    /*
        * @return \yii\db\ActiveQuery
        * получаем список подвязанных RDP доступов по юзеру, котор. мон может использовать для подключения к серваку
        */
    public function getAccess()
    {
        return $this->hasOne(Access::className(), ['id' => 'access_id'])->viaTable(UserAccess::tableName(), ['user_id'=>'id']);
    }

    public function getAccessServer(){
        return $this->access ? $this->access->server : 'Нет';
    }

    public function getAccessLogin(){
        return $this->access ? $this->access->login: 'Нет';
    }

    public function getAccessPass(){
        return $this->access ? $this->access->pass : 'Нет';
    }

    /*
     * получаем данные по авторизация по юзеру
     */
    public function getAuthLog()
    {
        return $this->hasMany(AuthLog::className(), ['user_id'=>'id'])->orderBy('create_at DESC');
    }

    /*
     * получаем данные по авторизация по юзеру
     */
    public function getAuthLogLast()
    {
        return $this->hasMany(AuthLog::className(), ['user_id'=>'id'])->orderBy('create_at DESC')->one();
    }


    /*
     * получаем данные фин. операциям по юзеру
     */
    public function getFinancy()
    {
        return $this->hasMany(Financy::className(), ['user_id'=>'id'])->orderBy(['create_at'=>'SORT_DESC']);
    }

    /*
     * обновим дату визита юзера
     * укажем IP и страну пользователя
     */
    public static function afterLogin()
    {

        //получаем последнее значение в таблице лога, а потом лишь записываем новые данные(юзер уже авторизирован)
        $user = User::findOne(Yii::$app->user->id);


        //запишим в сессию почту юзера
        \Yii::$app->session->set('user.email',$user->email);

        $info  = $user->authLogLast;

        //есть данные для обновления профиля пользователя - обновим
        if($info){
            Yii::$app
                ->db
                ->createCommand('UPDATE `user` SET last_visit_ip=:last_vizit_ip,last_vizit_time=:last_vizit_time WHERE id=:user_id')
                ->bindValues([':user_id'=>Yii::$app->user->id, ':last_vizit_time'=>$info->create_at, ':last_vizit_ip'=>$info->ip])
                ->execute();
        }

        //запишим данные по тек. входу пользователя
        $log = new AuthLog();

        //определяем страну пользователя
        $country = Yii::$app->sypexGeo->getCityFull(Yii::$app->request->userIP);//Yii::$app->getRequest()->getUserIP()

        $log->country = $country['country']['name_ru'];

        if($log->validate()){
            $log->save();
        }else{
            Yii::$app->log(print_r($log->errors), true);
        }


    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'Этот адрес почты уже занят.'],


            [['status','balance','last_vizit_time'], 'integer'],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            ['api_key', 'default', 'value'=>$this->getApiKey()],

            ['created_at', 'default', 'value'=>time()],//дата регистрации пользователя
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Обновлён',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'status' => 'Статус',
            'balance'=>'Баланс',
            'statusname'=>'Статус',
            'last_vizit_ip'=>'IP-адрес последнего входа',
            'last_vizit_time'=>'',
            'api_key'=>'API-ключ',
            'access'=>'Доступ',
        ];
    }


    /*
     * генерируем уникальный апи-ключ для пользователя
     * при его регистрации
     */
    public function getApiKey(){

        $list = [
            strtoupper(substr(md5(time()), 0, 5)),
            strtoupper(substr(md5(time()), 5, 5)),
            strtoupper(substr(md5(time()), 10, 5)),
            strtoupper(substr(md5(time()), 15, 5)),
            strtoupper(substr(md5(time()), 20, 5))
        ];

        $key = implode('-', $list);

        //поиск совпадения
        $find = User::find()->where(['api_key'=>$key])->one();

        //нашли совпадение - перегенерация ключа
        if($find){
            $this->getApiKey();
        }

        return $key;

    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /*
     * get list admins from params list
     */
    public function getAdmins(){
        return Yii::$app->params['admins'];
    }

    /*
     * check is admin user or not
     * $username - если не указан юзер. то проверяем по текущему юзеру
     */
    public function isAdmin($username = ''){

        if(!Yii::$app->user->isGuest){

            if(empty($username)){
                $username = \Yii::$app->session->get('user.email');
            }

            if(in_array($username, \Yii::$app->user->identity->admins)){
                return true;
            }
        }

        return false;
    }

    public function getAdmin(){
        if(!Yii::$app->user->isGuest){
            if(in_array(Yii::$app->user->identity->username, Yii::$app->user->identity->admins)){
                return true;
            }
        }

        return false;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function getBalance(){
        return Yii::$app->db
            ->createCommand('SELECT balance FROM '.User::tableName().' WHERE id=:id')
            ->bindValues([':id'=>Yii::$app->user->id])
            ->queryScalar();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
}
