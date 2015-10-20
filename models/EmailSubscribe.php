<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email_subscribe".
 *
 * @property integer $id
 * @property string $email
 * @property integer $ok
 * @property integer $date
 */
class EmailSubscribe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_subscribe';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['ok', 'date'], 'integer'],
            ['ok','validateOk', 'skipOnEmpty'=>false],
            [['email'], 'string', 'max' => 128],
            [['email'], 'unique','message'=>'Вы уже подавали заявку'],
            ['date', 'default', 'value'=>time()],
        ];
    }

    /*
     * только первым 50ти ставим = 1, остальным - 0
     */
    public function validateOk(){

        if(!$this->hasErrors()){

            $count = EmailSubscribe::find()->count();

            if($count>=50){
                $this->ok = 0;
            }else{
                $this->ok = 1;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Почта',
            'ok' => 'Попал юзер на бета-тестирование или нет',
            'date' => 'дата',
        ];
    }
}
