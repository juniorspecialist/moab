<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property integer $create_at
 * @property string $country
 *
 * @property User $user
 */
class AuthLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[[ 'user_id', 'ip', 'create_at'], 'required'],
            [[ 'user_id', 'create_at'], 'integer'],
            [['ip'], 'string', 'max' => 50],
            [['ip'], 'default', 'value' => Yii::$app->request->userIP],
            [['create_at'], 'default', 'value' => time()],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
            [['country'], 'string', 'max' => 80],
            [['country'], 'default', 'value' => ''],
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
            'ip' => 'IP',
            'create_at' => 'Дата',
            'country' => 'Страна',
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
     * @return AuthLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthLogQuery(get_called_class());
    }
}
