<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.07.15
 * Time: 10:54
 */
/*
 * форма отправки данных на Мерчанд - Вэб-мани
 */
?>

<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" id="webmoney_form" >
    <input type="hidden" name="LMI_PAYMENT_NO" value="1">
    <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$amount?>">
    <input type="hidden" name="LMI_PAYMENT_DESC" value="<?=iconv('utf-8','windows-1251','Пополнение баланса на сайте cabonet.moab.pro')?>">
    <input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$purse?>">
    <input type="hidden" name="id" value="<?=$id?>">

    <input type="submit" value="Перейти к оплате">
</form>

<script type="application/javascript">
    document.getElementById("webmoney_form").submit();
</script>

<?php
//$this->registerJs("$(document).ready(function(){
//    $('#webmoney_form').submit();
//});", $this::POS_READY);
