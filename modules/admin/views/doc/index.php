<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.08.15
 * Time: 9:49
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Пользователь',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::a($data->user->email,['modal', 'id'=>$data->id], ['class'=>'modal_photo','alt_id'=>$data->id]);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Документ',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::a($data->type,['modal', 'id'=>$data->id], ['class'=>'modal_photo','alt_id'=>$data->id]);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Статус',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::a($data->statusText,['modal', 'id'=>$data->id], ['class'=>'modal_photo','alt_id'=>$data->id]);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Дата загрузки',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::a(date('d-m-Y H:i:s',$data->uploaded),['modal', 'id'=>$data->id], ['class'=>'modal_photo','alt_id'=>$data->id]);
                },
            ],
        ],
    ]); ?>

</div>

<?php

\yii\bootstrap\Modal::begin([
    'header' => $this->render('_form'),
    //'size'=>\yii\bootstrap\Modal::SIZE_DEFAULT,
    'toggleButton' => [
        //'label' => !$subs->isNewRecord ? 'Продлить' : 'Подписаться',
        //'tag'=>'button',
        //'class'=>'btn btn-primary',
        'style'=>'display:none',
    ],
    //'footer'=>$this->render('_form'),
    'id'=>'modalWinPhoto',
]);
\yii\bootstrap\Modal::end();

?>

<style>
    .modal-content {
        width: 850px;
        margin-left: -200px;
    }
</style>
