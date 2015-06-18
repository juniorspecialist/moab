<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "base".
 *
 * @property integer $id
 * @property string $title
 *
 * @property UserSubscription[] $userSubscriptions
 */
class Base extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'price'], 'required'],
            [['id', 'price'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['user_info'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'price'=>'Цена',
            'user_info'=>'Пользовательская информация',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSubscriptions()
    {
        return $this->hasMany(UserSubscription::className(), ['base_id' => 'id']);
    }
}
