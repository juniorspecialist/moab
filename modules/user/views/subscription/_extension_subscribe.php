<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.06.15
 * Time: 11:20
 */
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$formModal = ActiveForm::begin(['action'=>['/subscribe','id'=>$subs_old->id],'options' => ['class'=>'extension-subscribe-form','id' => 'extension-subscribe-form'.$subs_old->id]]);
?>

<?=$formModal->errorSummary($subs);?>

<?= $formModal->field($subs, 'id')->hiddenInput()->label(false); ?>

<?= $formModal->field($subs, 'base_id')->hiddenInput(['value'=>$model->id])->label(false); ?>

<?= $formModal->field($subs, 'one_month')->checkbox()
    ->label($model->one_month_user_info); ?>
<?= $formModal->field($subs, 'three_month')->checkbox()
    ->label($model->three_month_user_info); ?>
<?= $formModal->field($subs, 'six_month')->checkbox()
    ->label($model->six_month_user_info);
?>
<?= $formModal->field($subs, 'twelfth_month')
    ->checkbox()
    ->label($model->twelfth_month_user_info); ?>
<?php
echo Html::submitButton('Подписаться',
    [
        'class' =>  'btn btn-primary subscribe-btn',
        'style'=>'width:167px'
    ]) ;

ActiveForm::end();

?>