<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.11.15
 * Time: 23:23
 */

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Финансы :';
//$this->params['breadcrumbs'][] = $this->title;
?>


<div class="base-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Дата',
                'format'=>'raw',
                'value' => function ($data) {
                    return date('d-m-Y H:i:s',$data->create_at);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Пользователь',
                'attribute'=>'user_id',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->user->email;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Вид операции',
                'attribute'=>'type_operation',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->typeOperation;
                },
                'filter'=>Html::activeDropDownList($searchModel, 'type_operation', \app\models\Financy::getListOperation(),['class'=>'form-control','prompt' => '']),
            ],

            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Статус',
                'attribute'=>'status',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->statusName;
                },
                'filter'=>Html::activeDropDownList($searchModel, 'status', \app\models\Financy::getStatusList(),['class'=>'form-control','prompt' => '']),
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Описание',
                'attribute'=>'desc',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->desc;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Сумма',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->amount.' руб.';
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Баланс',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->balance_after.' руб.';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template'=>'{update}'
                'visible'=>false,
            ],

        ],
    ]); ?>

</div>