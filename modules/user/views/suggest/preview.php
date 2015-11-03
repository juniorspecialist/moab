<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.10.15
 * Time: 23:31
 */

use yii\grid\GridView;

$this->title = 'Предварительный просмотр: выборка "'.$model->selections->name.'"';
//$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Выборки: '.$base->title;
$this->params['breadcrumbs'][] = $this->title;

//если нет данных по вордстату - не отображаем столбцы по вордстату
$columns = [
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Ключевая фраза',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->phrase;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Количество слов в исходной фразе',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->length;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Позиция подсказки',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->position;
                },
            ]
        ];
if($model->need_wordstat == \app\models\Selections::YES){
    $columns = \yii\helpers\ArrayHelper::merge(
        $columns,
        [
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'слово1 слово2',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->wordstat_1;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'"!слово1 !слово2"',
                'format'=>'raw',
                'value' => function ($data) {
                    return  $data->wordstat_3;
                },
            ]
        ]);
}

?>


<?=GridView::widget([
    //'summary'=>false,
    'id' => 'suggest-wordstat-grid-preview',
    'dataProvider' => $dataProvider,
    'columns' =>$columns,
]);
?>