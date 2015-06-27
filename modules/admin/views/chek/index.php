<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.15
 * Time: 16:17
 */


use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета';
$this->params['breadcrumbs'][] = $this->title;
?>
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
                'label'=>'№ Счета',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->id;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Сумма',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->amount;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Системы оплаты',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->paysystem;
                },
            ],

            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Пользователь',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->user->email;
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