<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.08.15
 * Time: 16:53
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = 'Выборки: Яндекс-Метрика';
//$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo Html::button('Управление группами',['id'=>'category_modal_btn','value'=>'/user/category/index']);

?>
<?php


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
    /*.modal{*/
        /*display: block !important; */
        /* I added this to see the modal, you don't need this */
    /*}*/

    /* Important part */
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        /*height: 450px;*/
        overflow-y: auto;
    }
</style>