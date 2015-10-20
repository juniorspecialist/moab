<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Базы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-index">

    <p>
        <?= Html::a('Создать Базу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'tableOptions' => [
//            'class' => 'table table-striped table-bordered'
//        ],
        'columns' => [
            'title',
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'1 мес.',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->one_month_price.'<br>'.$data->one_month_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'3 мес.',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->three_month_price.'<br>'.$data->three_month_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'6 мес.',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->six_month_price.'<br>'.$data->six_month_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'12 мес.',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->twelfth_month_price.'<br>'.$data->twelfth_month_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Вечная',
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
