<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.15
 * Time: 14:42
 */
use yii\grid\GridView;
use app\components\widgets\ModalWinWithBtnWidget;
use yii\widgets\Pjax;

use yii\helpers\Html;
?>

<!-- автоматическое обновление таблицы выборок для пользователя -->

<?php
    //Pjax::begin(['id'=>'suggest-grid-table','timeout'=>30000,'enablePushState'=>true])
?>

<?= Html::a("Обновить", \yii\helpers\Url::current(), ['class' => 'btn btn-lg btn-primary hide', 'id' => 'refreshButton']) ?>

<div class="selects-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>false,
        'id' => 'suggest-wordstat-grid',
//        'tableOptions' => [
//            'class' => 'table table-striped table-bordered'
//        ],
        'columns' => [
            [
                'class' => \yii\grid\CheckboxColumn::className(),
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Группа',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->category->title;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Ключевая фраза',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->name;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Кол-во результатов',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::tag('span', $data->getResultCountGrid(),['class'=>'result_count_'.$data->id]);
                },
                'options'=>['class'=>'result_count'],
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Дата создания',
                'format'=>'raw',
                'value' => function ($data) {
                    // с точностью до секунды в формате 15.10.2015 13:25:41
                    return date('d.m.Y H:i:s', $data->date_created);
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Статус',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::tag('span', $data->getStatusGrid(),['class'=>'status_'.$data->id]);
                },
                'options'=>['class'=>'status'],
            ],

           [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Параметры',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::tag(['span', $data->getParamsInfo(),'params_'.$data->id]);
                },
            ],

            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Просмотр',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::tag('span', $data->getPreviewGrid(), ['class'=>'preview_'.$data->id]);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Скачать',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::tag('span', $data->getLinkGrid(), ['class'=>'download_'.$data->id]);
                },
            ],
        ],
    ]); ?>

</div>

<?php

//Pjax::end();
