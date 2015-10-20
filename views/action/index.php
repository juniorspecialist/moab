<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-index">



    <p>
        <?= Html::a('Добавить акцию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'period_from',
            'period_to',
            'promo',
            //'base_id',
            [
                'attribute' => 'base_id',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->base->title;
                },
            ],
//            [
//
//            ],
            // 'subscribe_period',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
            ],
        ],
    ]); ?>

</div>
