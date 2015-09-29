<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 *
 * @property User $user
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message'=>'Укажите название группы'],
            [['user_id'], 'integer'],
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
            ['title', 'unique', 'targetAttribute' => ['user_id','title'], 'message'=>'Название группы "{value}" уже добавлено'],

            [['title'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Группа',
            'user_id' => 'Пользователь',
            // a1 needs to be unique, but column a2 will be used to check the uniqueness of the a1 value

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
