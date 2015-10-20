<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preview".
 *
 * @property integer $id
 * @property integer $selection_id
 * @property string $phrase
 * @property integer $length
 * @property integer $position
 * @property integer $wordstat_1
 * @property integer $wordstat_2
 * @property integer $wordstat_3
 *
 * @property Selections $selection
 */
class Preview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['selection_id', 'phrase', 'length', 'position', 'wordstat_1', 'wordstat_2', 'wordstat_3'], 'required'],
            [['selection_id', 'length', 'position', 'wordstat_1', 'wordstat_2', 'wordstat_3'], 'integer'],
            [['phrase'], 'string', 'max' => 255]
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
            'phrase' => 'фраза',
            'length' => 'длина исходной фразы',
            'position' => 'позиция исходной фразы',
            'wordstat_1' => 'частота wordstat без синтаксиса',
            'wordstat_2' => 'частота wordstatс синтаксисом в кавычках',
            'wordstat_3' => 'частота wordstatс синтаксисом в кавычках и с восклицательным знаком',
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
