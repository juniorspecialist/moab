<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 16:08
 */
?>

<?php

    echo $form->field($model, 'need_wordstat')
        ->radioList([
            \app\models\Selections::NO=>'Нужно максимальное количество фраз',
            \app\models\Selections::YES=>'Нужны фразы с частотностью Wordstat',
        ],[
            'item' => function($index, $label, $name, $checked, $value) {

                $return = '<label class="modal-radio">';
                if($value == \app\models\Selections::NO)
                {
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"  checked=checked tabindex="3">';
                }else{
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '"   tabindex="3">';
                }

                $return .= '<i></i>';
                $return .= '<span>' . ucwords($label) . '</span>';
                $return .= '</label>';

                return $return;
            },


//            'item' => function ($index, $label, $name, $checked, $value) {
//                return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
//                \yii\bootstrap\Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
//            },
        ])->label(false);


?>

<span class="wordstat_selects_params" style="display: none">
    <?=$form->field($model, 'wordstat_syntax')->dropDownList(\app\models\Selections::getWordsStatSyntax(),['class'=>'fixed-width']);?>
    <div class="form-inline">
        <?=$form->field($model, 'wordstat_from')->textInput( [ 'class'=>'wordstat form-control', 'type'=>'number','min'=>1,'max'=>100000000,'value'=>1, 'style'=>'width:100%']);?>
        <?=$form->field($model, 'wordstat_to')->textInput([ 'class'=>'wordstat form-control', 'type'=>'number','min'=>1,'max'=>100000000,'value'=>100000000, 'style'=>'width:100%'])->label(' до ');?>
    </div>
</span>

