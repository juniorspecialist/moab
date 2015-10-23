<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.10.15
 * Time: 14:41
 */
use yii\helpers\Html;
use yii\bootstrap\Modal;


echo Html::a('Управление группами', '#',['class'=>'btn btn-success control' ,'id'=>'category_modal_btn','value'=>'/user/category/index']);
//echo Html::button('Управление группами',['id'=>'category_modal_btn','value'=>'/user/category/index']);

Modal::begin([
    'header' => '<h2>Группы</h2>',
    'id'=>'modal_control_category',
    //'class'=>'modal-open-scroll',
    'toggleButton' => [
        'label' => 'Управление группами',
        //'id'=>'category_modal_btn',
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