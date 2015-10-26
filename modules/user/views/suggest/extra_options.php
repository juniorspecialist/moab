<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 14:46
 */

echo $form->field($model, 'potential_traffic')->dropDownList(\app\models\Selections::getPotencialTraffic());

echo $form->field($model,'source_words_count_from')->textInput(['type'=>'number','min'=>1,'max'=>32, 'class'=>'extra_options','disabled'=>'disabled']);
echo $form->field($model,'source_words_count_to')->textInput(['type'=>'number','min'=>1,'max'=>32, 'class'=>'extra_options','disabled'=>'disabled']);

echo $form->field($model,'position_from')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>1, 'class'=>'extra_options','disabled'=>'disabled']);
echo $form->field($model,'position_to')->textInput(['type'=>'number','min'=>1,'max'=>10,'value'=>10, 'class'=>'extra_options','disabled'=>'disabled']);

echo $form->field($model,'suggest_words_count_from')->textInput(['type'=>'number','min'=>1,'max'=>32]);
echo $form->field($model,'suggest_words_count_to')->textInput(['type'=>'number','min'=>1,'max'=>32]);


echo $form->field($model,'length_from')->textInput(['type'=>'number','min'=>1,'max'=>256]);
echo $form->field($model,'length_to')->textInput(['type'=>'number','min'=>1,'max'=>256]);