<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.10.15
 * Time: 17:37
 */
use yii\helpers\Html;
use yii\bootstrap\Modal;

Modal::begin([
    'header' => "<h2>$header</h2>",
    'id'=>uniqid(time().rand(1,1000)),
    'toggleButton' => [
        'label' => $button_label,
        //'style'=>'display:none',
        'tag'=>'a'
    ],
]);

echo $info;

Modal::end();
?>

<style>
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        /*height: 450px;*/
        overflow-y: auto;
    }
</style>