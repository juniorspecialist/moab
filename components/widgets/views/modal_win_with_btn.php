<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.10.15
 * Time: 17:37
 */
use yii\helpers\Html;
use yii\bootstrap\Modal;


echo Html::a($button_label, '#',['class'=>'btn btn-success control' ,'id'=>'category_modal_btn','value'=>'/user/category/index']);

Modal::begin([
    //'header' => '<h2>Группы</h2>',
    'id'=>'modal_control_category',
    'toggleButton' => [
        'label' => 'Управление группами',
        'style'=>'display:none',
    ],
]);
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