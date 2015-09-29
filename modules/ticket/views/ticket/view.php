<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.15
 * Time: 14:21
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;


$this->title = 'Тикет #'.$model->id;
$this->params['breadcrumbs'][] = $this->title;




echo DetailView::widget(['model' => $model,
    'attributes' => [
        'id',
        [
            'label'=>'Дата создания',
            'value'=>date('d-m-Y H:i:d', $model->created),
        ],
        [
            'label'=>'Приоритет',
            'format'=>'raw',
            'value'=>$model->getPrioritetColor(),
        ],
        [
            'label'=>'Тема',
            'value'=>Html::encode($model->theme),
        ],
        [
            'label'=>'Статус',
            'value'=>$model->statusName,
        ],
    ],
]);

//выводим список ответов по тикету
Pjax::begin(['id' => 'answers_ticket']);
if($answers){
    foreach($answers as $answer){
        echo   $this->render('@app/modules/ticket/views/admin/answer', ['answer'=>$answer]);
    }
}
Pjax::end();


//форма для ответа по тикету
if($model->status==\app\models\Tickets::STATUS_OPEN){
    echo $this->render('@app/modules/ticket/views/admin/_answer_form',['model'=>$answer_form]);
}

//форма для открытия закрытого тикета в системе
if($model->status==\app\models\Tickets::STATUS_CLOSE){
    echo $this->render('@app/modules/ticket/views/admin/_open_form',['model'=>$answer_form]);
}