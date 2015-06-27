<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.15
 * Time: 9:55
 */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Финансы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financy-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'create_at',
                'format'=>'raw',
                'value' => function ($data) {
                    return date('d-m-Y H:i:s',$data->create_at);
                },
            ],
            [
                'attribute' => 'create_at',
                'format'=>'raw',
                'label'=>'Вид операции',
                'value' => function ($data) {
                    return $data->typeOperation;
                },
            ],
            'desc',
            [
                'format'=>'raw',
                'attribute'=>'amount',
                'value'=>function($data){
                    return $data->amount.' руб';
                }
            ],
            [
                'format'=>'raw',
                'attribute'=>'balance_after',
                'value'=>function($data){
                    return $data->balance_after.' руб.';
                }
            ],
        ],
    ]); ?>

</div>