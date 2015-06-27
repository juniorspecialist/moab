<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.15
 * Time: 16:31
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Финансы по пользователю :'.$model->email;
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<h2>История авторизаций пользователя:--><?php //echo $model->email;?><!--</h2>-->

<div class="base-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
                'label'=>'Вид операции',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->typeOperation;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Описание',
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