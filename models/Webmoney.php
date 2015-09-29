<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.07.15
 * Time: 16:16
 */

namespace app\models;


use yii\base\Object;

class Webmoney extends Object{

    public $baseUrl = 'https://merchant.webmoney.ru/lmi/payment.asp';

    public $LMI_PAYEE_PURSE  = '';//номер кошелька

    /*
     * 0 или отсутствует: Для всех тестовых платежей сервис будет имитировать успешное выполнение;
        1: Для всех тестовых платежей сервис будет имитировать выполнение с ошибкой (платеж не выполнен);
        2: Около 80% запросов на платеж будут выполнены успешно, а 20% - не выполнены.
     */
    public $LMI_SIM_MODE = true;//режим тестирования

    public $Secret_Key = '';//секретный ключ безопастности

    /*
     * <form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">
<input type="hidden" name="LMI_PAYMENT_NO" value="1">
<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="0.05">
<input type="hidden" name="LMI_PAYMENT_DESC" value="код пополнения Super Mobile">
<input type="hidden" name="LMI_PAYEE_PURSE" value="Z155771820786">
<input type="hidden" name="id" value="345">
Укажите email для отправки товара: <input type="text" name="email" size="15">
<input type="submit" value="Перейти к оплате">
</form>
     */
    public function payment($nOutSum, $nInvId, $sInvDesc = null, $sIncCurrLabel=null, $sEmail = null, $sCulture = null, $shp = [])
    {
        $url = $this->baseUrl;
        $signature = "{$this->sMerchantLogin}:{$nOutSum}:{$nInvId}:{$this->sMerchantPass1}";

        $sSignatureValue = sha1($signature);
        $url .= '?' . http_build_query([
                'MrchLogin' => $this->sMerchantLogin,
                'OutSum' => $nOutSum,
                'InvId' => $nInvId,
                'Desc' => $sInvDesc,
                'SignatureValue' => $sSignatureValue,
                'IncCurrLabel' => $sIncCurrLabel,
                'Email' => $sEmail,
                'Culture' => $sCulture,
            ]);
        if (!empty($shp) && ($query = http_build_query($shp)) !== '') {
            $url .= '&' . $query;
        }



        Yii::$app->user->setReturnUrl(Yii::$app->request->getUrl());

        //\Yii::$app->response->

        return Yii::$app->response->redirect($url);
    }
}