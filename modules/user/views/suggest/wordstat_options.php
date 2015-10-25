<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 16:08
 */
?>

<div class="alert alert-dismissable" >
    ВНИМАТЕЛЬНО прочитайте данный текст. База данных MOAB Suggests включает в себя данные по частотности Wordstat по популярным коммерческим и некоммерческим запросам.<br><br>

    Если вы не являетесь профильным специалистом и просто хотите получить как можно больше релевантных фраз, нажмите "Нужно максимальное количество фраз". В этом случае вы получите отчет, в котором будут представлены все фразы, собранные нами из Яндекс.Подсказок. В этом отчете не будут представлены данные по Wordstat, однако вы получите максимальное количество фраз, релевантных вашему бизнесу, с трафиком, отличным от нуля.
    <br><br>

    Если вы являетесь профильным специалистом и хотите получить фразы с частотностью Wordstat, нажмите "Нужны фразы с частотностью Wordstat", и задайте требуемую частотность Wordstat. Обращаем ваше внимание, что
    частотность Wordstat определена для фраз, содержащих 7 слов или менее. Таким образом, в отчете будут отсутствовать фразы, содержащие 8 слов и более, а также фразы, имеющие нулевую частотность по Wordstat. Обращаем ваше внимание также на то, что нередко фразы имеющие частотность 0 по Wordstat, в реальности могут приносить значительный трафик, связанный с неточностями отображения данных в Yandex. Данные предоставляются для точной частотности вида "!слово1 !слово2".
</div>

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
            }

//            'item' => function ($index, $label, $name, $checked, $value) {
//                return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
//                \yii\bootstrap\Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
//            },
        ])->label(false);


?>

<span class="wordstat_selects_params" style="display: none">
    <?php

        echo $form->field($model, 'wordstat_syntax')->dropDownList(\app\models\Selections::getWordsStatSyntax(), [ 'class'=>'wordstat']);

        echo $form->field($model, 'wordstat_from')->textInput( [ 'class'=>'wordstat', 'type'=>'number','min'=>1,'max'=>100000000,'value'=>1]);

        echo $form->field($model, 'wordstat_to')->textInput([ 'class'=>'wordstat', 'type'=>'number','min'=>1,'max'=>100000000,'value'=>100000000]);
    ?>
</span>

