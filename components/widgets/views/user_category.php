<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.10.15
 * Time: 14:41
 */
use yii\helpers\Html;
use yii\bootstrap\Modal;


echo Html::a('<i class="fa fa-wrench"></i> Управление группами', '#',['class'=>'btn btn-warning control' ,'id'=>'category_modal_btn','value'=>'/user/category/index']);

Modal::begin([
    'header' => '<h4>Группы</h4>',
    'id'=>'modal_control_category',
    'toggleButton' => [
        'label' => 'Управление группами',
        'style'=>'display:none',
    ],

    'clientEvents'=>[
        'hide.bs.modal'=>'function(){
            if($("#can_we_refrash_page").val()==1){
                document.location.reload(true);
                return true;
            }}'
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