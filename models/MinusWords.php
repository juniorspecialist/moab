<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "minus_words".
 *
 * @property integer $id
 * @property integer $selection_id
 * @property string $minus_word
 *
 * @property Selections $selection
 */
class MinusWords extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'minus_words';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['selection_id', 'minus_word'], 'required'],
            [['selection_id'], 'integer'],
            [['minus_word'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'selection_id' => 'Selection ID',
            'minus_word' => 'Minus Word',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSelection()
    {
        return $this->hasOne(Selections::className(), ['id' => 'selection_id']);
    }
}
