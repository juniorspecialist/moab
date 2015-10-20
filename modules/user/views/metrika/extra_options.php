<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 14:46
 */

echo $form->field($model, 'potential_traffic')->dropDownList(\app\models\Selections::getPotencialTraffic());


echo \yii\helpers\Html::label('Количество слов в исходной фразе:  От  ');
echo \yii\helpers\Html::activeDropDownList($model, 'source_words_count_from',\app\models\Selections::getNumberList());


echo \yii\helpers\Html::label(' До  ');
echo \yii\helpers\Html::activeDropDownList($model, 'source_words_count_to', \app\models\Selections::getNumberList());


echo '<br><br>';

echo \yii\helpers\Html::label('Позиция подсказки:  От  ');
echo \yii\helpers\Html::activeDropDownList($model, 'position_from', \app\models\Selections::getNumberList());


echo \yii\helpers\Html::label(' До  ');
echo \yii\helpers\Html::activeDropDownList($model, 'position_to', \app\models\Selections::getNumberList());


echo '<br><br>';
//suggest_words_count_from
echo \yii\helpers\Html::label('Количество слов в подсказке:  От  ');
echo \yii\helpers\Html::activeDropDownList($model, 'suggest_words_count_from', \app\models\Selections::getNumberList());


echo \yii\helpers\Html::label(' До  ');
echo \yii\helpers\Html::activeDropDownList($model, 'suggest_words_count_to', \app\models\Selections::getNumberList());


echo '<br><br>';
//suggest_words_count_from
echo \yii\helpers\Html::label('Длина подсказки (симв.):  От  ');
echo \yii\helpers\Html::activeDropDownList($model, 'length_from', \app\models\Selections::getNumberList());


echo \yii\helpers\Html::label(' До  ');
echo \yii\helpers\Html::activeDropDownList($model, 'length_to', \app\models\Selections::getNumberList());