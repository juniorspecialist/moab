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
<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'create_at',
                'format'=>'raw',
                'value' => function ($data) {
                    return date('d-m-Y H:i:s',$data->create_at);
                },
            ],
//            'username',
//            'email',
//            'statusname',
//            'balance',
        ],
    ]); ?>

</div>