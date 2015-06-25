<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.15
 * Time: 16:07
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История авторизаций:'.$model->email;
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
                'label'=>'IP',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->ip;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Страна',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->country;
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
