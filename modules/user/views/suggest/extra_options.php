<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 14:46
 */

$extra_options = [];
if($model->potential_traffic !== \app\models\Selections::POTENCIAL_TRAFFIC_USER){
    $extra_options = ['disabled'=>'disabled'];
}
?>


<?=$form->field($model, 'potential_traffic')->dropDownList(\app\models\Selections::getPotencialTraffic());?>

<div class="form-inline">
    <?=$form->field($model,'source_words_count_from')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['class'=>'extra_options  form-control', 'style'=>'width:100%']));?>
    <?=$form->field($model,'source_words_count_to')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['class'=>'extra_options  form-control', 'style'=>'width:100%']))->label(' до ');?>
</div>

<div class="form-inline">
    <?=$form->field($model,'position_from')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['value'=>1, 'class'=>'extra_options form-control', 'style'=>'width:100%']));?>
    <?=$form->field($model,'position_to')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['value'=>10, 'class'=>'extra_options form-control', 'style'=>'width:100%']))->label(' до ');?>
</div>

<div class="form-inline">
    <?=$form->field($model,'suggest_words_count_from')->textInput(['class'=>'other_extra_options form-control', 'style'=>'width:100%']);?>
    <?=$form->field($model,'suggest_words_count_to')->textInput(['class'=>'other_extra_options form-control', 'style'=>'width:100%'])->label(' до ');?>
</div>


<div class="form-inline">
    <?=$form->field($model,'length_from')->textInput(['class'=>'other_extra_options form-control', 'style'=>'width:100%']);?>
    <?=$form->field($model,'length_to')->textInput(['class'=>'other_extra_options form-control', 'style'=>'width:100%'])->label(' до ');?>
</div>