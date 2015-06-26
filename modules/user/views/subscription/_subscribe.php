<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.15
 * Time: 9:59
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$subs = new \app\models\UserSubscription();

if($subsriptions){
    $find = false;
    foreach($subsriptions as $subsription){
        if($subsription->base_id==$model->id){
            $find = true;
            $subs = $subsription;break;
        }
    }
}
?>

    <?php $form = ActiveForm::begin(['action'=>'/subscribe']); ?>

        <tr data-key="<?=$index;?>">
            <td>
                <?=$model->title;?>
                <?= $form->field($subs, 'base_id')->hiddenInput(['value'=>$model->id])->label(false); ?>
            </td>
            <td><?= $form->field($subs, 'one_month')->checkbox(['disabled'=>!$subs->isNewRecord,'label'=>''])->label($model->one_month_price.' руб.'); ?></td>
            <td><?= $form->field($subs, 'three_month')->checkbox(['disabled'=>!$subs->isNewRecord,'label'=>''])->label($model->three_month_price.' руб.'); ?></td>
            <td><?= $form->field($subs, 'six_month')->checkbox(['disabled'=>!$subs->isNewRecord,'label'=>''])->label($model->six_month_price.' руб.'); ?></td>
            <td><?= $form->field($subs, 'twelfth_month')->checkbox(['disabled'=>!$subs->isNewRecord,'label'=>''])->label($model->twelfth_month_price.' руб.'); ?></td>
            <td><?= Html::submitButton(!$subs->isNewRecord ? 'Подписка оформлена' : 'Подписаться', ['disabled'=>!$subs->isNewRecord,'class' => $subs->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style'=>'width:167px']) ?></td>
            <td><?=$subs->desc?></td>
            <td>
                <?= Html::hiddenInput('continue',true); ?>
                <?= Html::submitButton('Продлить', ['style'=>!($subs->isNewRecord)?'width:167px':'width:167px; display:none','class' =>  'btn btn-primary']) ?>
            </td>
        </tr>

<?php ActiveForm::end(); ?>