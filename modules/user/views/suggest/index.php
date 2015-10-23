<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 14:34
 */
use yii\helpers\Html;
use app\components\widgets\UserCategoryWidget;
use yii\grid\GridView;



$this->title = 'Выборки: Яндекс-Подсказки';
//$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="suggest_wordstat_control col-md">
    <div class="suggest_wordstat_buttons col-md-4">
        <?php

            echo UserCategoryWidget::widget();

            echo Html::a('Создать выборку', ['create'] ,['class'=>'btn btn-success control']);

            echo Html::a('Удалить отмеченные выборки', '#' ,['class'=>'btn btn-success control', 'id'=>'delete_checked_selects_btn','delete'=>\yii\helpers\Url::to(['delete'])]);
        ?>
</div>
<div class="suggest_wordstat_base_info col-md-4">
    <?php echo Html::tag('div',$base->getAttributeLabel('last_update').':'.$base->getAttribute('last_update'),['class'=>'last_update']);?>
    <?php echo Html::tag('div',$base->getAttributeLabel('next_update').':'.$base->getAttribute('next_update'),['class'=>'next_update']);?>
    <?php echo Html::tag('div',$base->getAttributeLabel('count_keywords').':'.$base->getAttribute('count_keywords'),['class'=>'next_update']);?>
    <?php echo Html::tag('div',$base->getAttributeLabel('add_in_update').':'.$base->getAttribute('add_in_update'),['class'=>'next_update']);?>
</div>
</div>

<div class="search-suggest-wordstat col-md-12">
    <form method="get"  action="search">

        <div class="input-group ">

            <a class="clear ng-hide"  tabindex="0" aria-hidden="true"></a>

        <span class="input-group-btn">
            <button class="btn" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </span>

            <input type="text" id="search_field" class="form-control ng-pristine ng-valid ng-touched" placeholder="Введите значение фильтра"  tabindex="0" aria-invalid="false" style="width: 90%">

            <a href="#" class="clear-search" onclick="$('#search_field').val('');return false;"> <i class="fa fa-times"></i> </a>

        </div>
    </form>
</div>




<div class="selects-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
                    return $data->one_month_price.'<br>'.$data->one_month_user_info;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Ключевая фраза',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->three_month_price.'<br>'.$data->three_month_user_info;
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
                        return $data->results_count;
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
                    return $data->getStatusName();
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Параметры',
                'format'=>'raw',
                'value' => function ($data) {
                    //Ссылка/кнопка на всплывающее окно с параметрами выборки. В этом всплывающем окне будет выводиться информация о выборке,
                    return $data->getModalWindowInfo();
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Просмотр',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->eternal_period_price.'<br>'.$data->eternal_period_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Скачать',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->eternal_period_price.'<br>'.$data->eternal_period_user_info; // $data['name'] for array data, e.g. using SqlDataProvider.
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}'
            ],
        ],
    ]); ?>

</div>