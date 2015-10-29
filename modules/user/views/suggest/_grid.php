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
    Pjax::begin(['id'=>'suggest-grid-table','timeout'=>10000])
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
                    //Высвечивается только для выборок в статусе «Выполнена». Для остальных статусов –пустая строка.
                    if($data->status==\app\models\Selections::STATUS_DONE)
                    {
                        return  \Yii::$app->formatter->asInteger($data->results_count);
                    }
                    return '';
                },
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
                    //ожидает
                    if($data->status == \app\models\Selections::STATUS_WAIT){
                        $class = ' <i class="fa fa-clock-o"></i>';
                    }
                    //выполняется
                    if($data->status == \app\models\Selections::STATUS_EXECUTE){
                        $class = '<i class="fa fa-refresh fa-spin"></i>';
                    }//выполнено
                    if($data->status == \app\models\Selections::STATUS_DONE){
                        $class = '<i class="fa fa-check"></i>';
                    }
                    return $class.'&nbsp;'.$data->getStatusName();
                },
            ],

           /* [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Параметры',
                'format'=>'raw',
                'value' => function ($data) {
                    //Ссылка/кнопка на всплывающее окно с параметрами выборки. В этом всплывающем окне будет выводиться информация о выборке,
                    return Html::a('Параметры', ['#'],['modal_info'=>$data->getTotalInfo(),'class'=>'suggest_params_modal_link']);
                    //return  ModalWinWithBtnWidget::widget(['info'=>$data->getTotalInfo(),'button_label'=>'Параметры','header'=>'Параметры выборки', 'id'=>'suggest_info_'.$data->id]);
                },
            ],*/

            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Просмотр',
                'format'=>'raw',
                'value' => function ($data) {
                    //Высвечивается только для выборок в статусе «Выполнена»
//                    if($data->status==\app\models\Selections::STATUS_DONE  && $data->results_count!=0)
//                    {
                        return Html::a('Просмотреть',\yii\helpers\Url::to(['/user/suggest/preview','id'=>$data->id]),[
                            'target'=>'_blank',
                            'class'=>'modal_preview_suggest'
                        ]);
//                    }
                    return '';
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Скачать',
                'format'=>'raw',
                'value' => function ($data) {
                    if($data->status==\app\models\Selections::STATUS_DONE && $data->results_count!=0){
                        return 'Скачать '. Html::a('TXT',$data->result_txt_zip,['target'=>'_blank']).' | '.Html::a('CSV',$data->result_csv_zip,['target'=>'_blank']);//.' | '.Html::a('XLSX', $data->result_xlsx_zip,['target'=>'_blank'])
                    }
                    return '';
                },
            ],
        ],
    ]); ?>

</div>

<?php

Pjax::end();
