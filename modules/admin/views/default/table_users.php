<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.15
 * Time: 11:38
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'title',
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Пользователь',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->email;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Баланс',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->balance;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Подписки',
                'format'=>'raw',
                'value' => function ($data) {
                    //есть данные по подпискам, по юзеру - получим список его подписок
                    if($data->subscription){
                        $info = '';
                        foreach($data->subscription as $userSubscription){
                            $info.=$userSubscription->Subscription->title.' до - '.date('d-m-Y',$userSubscription->to);
                        }
                        return $info;
                    }
                    return '';
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Инфо',
                'format'=>'raw',
                'value' => function ($data) {
                    return "Последний вход:".date('Y-m-d H:i:s', $data->authLogLast->create_at).'|'.
                        Html::a('История IP', ['/admin/default/history-ip', 'user_id'=>$data->id]).' | '.
                        Html::a('Финансы', ['/admin/default/financy', 'user_id'=>$data->id]);
                },
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template'=>'{update}'
//            ],

        ],
    ]); ?>

</div>
