<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "links".
 *
 * @property integer $id
 * @property string $link
 * @property integer $status
 */
class Links extends \yii\db\ActiveRecord
{

    const STATUS_IS_NEW = 0;
    const STATUS_WRITE_COOKIE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link', 'status'], 'required'],
            [['status'], 'integer'],
            [['link'], 'string', 'max' => 10],
            [['email'], 'string', 'max' => 60],
            [['link'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'status' => 'Status',
        ];
    }

    function generatePassword($length = 8){
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
}
