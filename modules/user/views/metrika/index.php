<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.08.15
 * Time: 16:53
 */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;

$this->title = 'Выборки: Яндекс-Метрика';
//$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


    echo Html::a('Управление группами', '#',['class'=>'btn btn-success' ,'id'=>'category_modal_btn','value'=>'/user/category/index','style'=>'float:left']);

    echo '<br><br>';

    echo Html::a('Создать выборку', ['create'] ,['class'=>'btn btn-success'],['style'=>'float:left']);

    echo '<br><br>';

    echo Html::a('Удалить отмеченные выборки', ['delete'] ,['class'=>'btn btn-success', 'id'=>'delete_checked_selects_btn'],['style'=>'float:left']);

    echo '<br><br>';

?>

<form method="get" class="search ng-pristine ng-valid ng-scope" action="/packages">
    <div class="input-group">

        <a class="clear ng-hide"  tabindex="0" aria-hidden="true"></a>

        <span class="input-group-btn">
            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
        </span>

        <input type="text" class="form-control ng-pristine ng-valid ng-touched" placeholder="Введите значение фильтра"  tabindex="0" aria-invalid="false" style="width: 90%">

<!--        <i class="fa fa-times"></i>-->

    </div>
</form>




<div class="selects-index">


<!--    * ```javascript-->
<!--    * var keys = $('#grid').yiiGridView('getSelectedRows');-->
<!--    * // keys is an array consisting of the keys associated with the selected rows    -->

<?= GridView::widget([
    'dataProvider' => $dataProvider,
//        'tableOptions' => [
//            'class' => 'table table-striped table-bordered'
//        ],
    'columns' => [

        [
            'class' => \yii\grid\CheckboxColumn::className(),
            //'attribute' => 'id',
            //'label'=>'',
        ],


        [
            'class' => 'yii\grid\DataColumn',
            'label'=>'Группа',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->one_month_price.'<br>'.$data->one_month_user_info;
            },
        ],
        [
            'class' => 'yii\grid\DataColumn',
            'label'=>'Ключевая фраза',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->three_month_price.'<br>'.$data->three_month_user_info;
            },
        ],
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label'=>'Кол-во результатов',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->six_month_price.'<br>'.$data->six_month_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],

        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label'=>'Дата создания',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->twelfth_month_price.'<br>'.$data->twelfth_month_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],

        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label'=>'Статус',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->eternal_period_price.'<br>'.$data->eternal_period_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],

        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label'=>'Параметры',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->eternal_period_price.'<br>'.$data->eternal_period_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],

        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label'=>'Просмотр',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->eternal_period_price.'<br>'.$data->eternal_period_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label'=>'Скачать',
            'format'=>'raw',
            'value' => function ($data) {
                return $data->eternal_period_price.'<br>'.$data->eternal_period_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
        ],


        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{update}'
        ],

    ],
]); ?>

</div>



<?php
//диалоговое окно для управления группами
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