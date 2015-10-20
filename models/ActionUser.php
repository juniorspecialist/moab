<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "action_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $create_d
 * @property integer $action_id
 *
 * @property Action $action
 */
class ActionUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'action_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action_id'], 'required'],
            [['user_id', 'action_id'], 'integer'],

            //[''],

            ['create_d', 'default', 'value'=>time()],
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'пользователь',
            'create_d' => 'дата активации',
            'action_id' => 'акция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(Action::className(), ['id' => 'action_id']);
    }
}
