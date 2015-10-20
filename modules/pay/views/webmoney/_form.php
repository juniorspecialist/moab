<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.07.15
 * Time: 16:42
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Пополнение баланса через Webmoney';


?>

<div class="financy-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount', [
        'inputOptions' => [
            'placeholder' => 'Сумма пополнения',
        ],
    ])->inline()->textInput(['maxlength' => 8,'style'=>'width:250px']); ?>

    <div class="form-group">
        <?= Html::submitButton('Пополнить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
if(!$model->hasErrors() && $model->validate()){
    ?>

    <form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" id="webmoney_form" style="display: none" accept-charset="windows-1251">
        <input type="hidden" name="LMI_PAYMENT_NO" value="<?=$id?>">
        <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$amount?>">
        <input type="hidden" name="LMI_PAYMENT_DESC" value="Пополнение баланса на сайте cabonet.moab.pro">
        <input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$purse?>">

        <input type="submit" value="Перейти к оплате">
    </form>

    <script type="application/javascript">
        document.getElementById("webmoney_form").submit();
    </script>
<?php
}
?>