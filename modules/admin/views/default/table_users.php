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
                    $info = '';
                    foreach($data->usersubscription as $usersubscription){
                        $info.=$usersubscription->base->title.' до '.date('d-m-Y',$usersubscription->to).'<br>';
                    }
                    return $info;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Инфо',
                'format'=>'raw',
                'value' => function ($data) {
                    return "Последний вход:".date('Y-m-d H:i:s', $data->authLogLast->create_at).'|'.
                        Html::a('История IP', Yii::$app->urlManager->createAbsoluteUrl(['admin/default/history-ip', 'id'=>$data->id])).' | '.
                        Html::a('Финансы', Yii::$app->urlManager->createAbsoluteUrl(['/admin/default/financy', 'id'=>$data->id]));
                },
            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                //'template'=>'{update}'
//            ],

        ],
    ]); ?>

</div>
