<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 14:46
 */

echo $form->field($model, 'potential_traffic')->dropDownList(\app\models\Selections::getPotencialTraffic());



echo $form->field($model,'source_words_count_from')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>1, 'class'=>'extra_options']);

echo $form->field($model,'source_words_count_to')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>10, 'class'=>'extra_options']);

//echo \yii\helpers\Html::label('Количество слов в исходной фразе:  От  ');
//echo \yii\helpers\Html::activeInput('number',$model, 'source_words_count_from',['min'=>1,'max'=>10,'value'=>1]);


//echo \yii\helpers\Html::label(' До  ');
//echo \yii\helpers\Html::activeInput('number',$model, 'source_words_count_to',['min'=>1,'max'=>10,'value'=>10]);


//echo '<br><br>';
//
//echo \yii\helpers\Html::label('Позиция подсказки:  От  ');
//echo \yii\helpers\Html::activeInput('number', $model, 'position_from',['min'=>1,'max'=>10,'value'=>1]);
//
//
//echo \yii\helpers\Html::label(' До  ');
//echo \yii\helpers\Html::activeInput('number', $model, 'position_to',['min'=>1,'max'=>10,'value'=>10]);

echo $form->field($model,'position_from')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>1, 'class'=>'extra_options']);

echo $form->field($model,'position_to')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>10, 'class'=>'extra_options']);



//echo '<br><br>';
////suggest_words_count_from
//echo \yii\helpers\Html::label('Количество слов в подсказке:  От  ');
//echo \yii\helpers\Html::activeInput('number', $model, 'suggest_words_count_from',['min'=>1,'max'=>10,'value'=>1]);
//
//
//echo \yii\helpers\Html::label(' До  ');
//echo \yii\helpers\Html::activeInput('number', $model, 'suggest_words_count_to',['min'=>1,'max'=>10,'value'=>10]);

echo $form->field($model,'suggest_words_count_from')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>1]);

echo $form->field($model,'suggest_words_count_to')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>10]);


/*
echo '<br><br>';
//suggest_words_count_from
echo \yii\helpers\Html::label('Длина подсказки (симв.):  От  ');
echo \yii\helpers\Html::activeInput('number', $model, 'length_from',['min'=>1,'max'=>10,'value'=>1]);


echo \yii\helpers\Html::label(' До  ');
echo \yii\helpers\Html::activeInput('number', $model, 'length_to',['min'=>1,'max'=>10,'value'=>10]);
*/

echo $form->field($model,'length_from')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>1]);

echo $form->field($model,'length_to')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>10]);