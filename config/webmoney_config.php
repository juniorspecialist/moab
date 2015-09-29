<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.07.15
 * Time: 10:16
 */

//массив настроек для WebMoney
return [
    'class' => 'app\models\Webmoney',
    'Secret_Key' => 'dflsj4k!;fm31212121555rrrrgdfg',
    'LMI_SIM_MODE' => true,//использовать тестовый режим или нет
    'LMI_PAYEE_PURSE' => 'R138052078269',//номер кошелька, для приема платежей
    //'testMode'=>true,//используем тестовый режим для отладки
];