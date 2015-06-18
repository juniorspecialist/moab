<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_access".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $access_id
 *
 * @property User $user
 * @property Access $access
 */
class UserAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'access_id'], 'required'],
            [['id', 'user_id', 'access_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'access_id' => 'Access ID',
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
     * @return \yii\db\ActiveQuery
     */
    public function getAccess()
    {
        return $this->hasOne(Access::className(), ['id' => 'access_id']);
    }

    /**
     * @inheritdoc
     * @return UserAccessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAccessQuery(get_called_class());
    }
}
