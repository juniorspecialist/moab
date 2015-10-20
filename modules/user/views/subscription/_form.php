<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.07.15
 * Time: 14:04
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;


// форма продления подписки
$form = ActiveForm::begin(['options' => ['class'=>'extension-subscribe-form']]);
?>
<?php echo $form->errorSummary($subs) ?>

    <table class="table">
    <tr>
        <td><?=$model->getAttribute('one_month_user_info');?></td>
        <td>1 месяц</td>
        <td><?= $form->field($subs, 'one_month')->checkbox(['label'=>''])->label(false)?></td>
    </tr>

    <tr>
        <td><?=$model->getAttribute('three_month_user_info');?></td>
        <td>3 месяца</td>
        <td><?= $form->field($subs, 'three_month')->checkbox(['label'=>''])->label(false)?></td>
    </tr>

    <tr>
        <td><?=$model->getAttribute('six_month_user_info');?></td>
        <td>6 месяцев</td>
        <td><?= $form->field($subs, 'six_month')->checkbox(['label'=>''])->label(false)?></td>
    </tr>

    <tr>
        <td><?=$model->getAttribute('twelfth_month_user_info');?></td>
        <td>12 месяцев</td>
        <td><?= $form->field($subs, 'twelfth_month')->checkbox(['label'=>''])->label(false)?></td>
    </tr>

    <tr>
        <td><?=$model->getAttribute('eternal_period_user_info');?></td>
        <td>Вечная</td>
        <td><?= $form->field($subs, 'eternal_period')->checkbox(['label'=>''])->label(false)?></td>
    </tr>

    <tr>
        <td colspan="3"><?=Html::submitButton(!$subs->isNewRecord ? 'Продлить' : 'Подписаться',['class' =>  'btn btn-primary subscribe-btn','style'=>'width:167px;align-content: center']) ;?></td>
    </tr>

<?php
ActiveForm::end();