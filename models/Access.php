<?php

namespace app\models;

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
            [['id', 'busy', 'login', 'pass', 'server'], 'required'],
            [['id', 'busy'], 'integer'],
            [['login', 'pass', 'server'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'busy' => 'Busy',
            'login' => 'Login',
            'pass' => 'Pass',
            'server' => 'Server',
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


}
