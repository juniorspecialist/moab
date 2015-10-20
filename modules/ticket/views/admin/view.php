<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.08.15
 * Time: 16:36
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
              'label'=>'Автор тикета',
              'value'=>$model->author->email,
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
        echo   $this->render('answer', ['answer'=>$answer]);
    }
}
Pjax::end();


//форма для ответа по тикету
if($model->status==\app\models\Tickets::STATUS_OPEN){
    echo $this->render('_answer_form',['model'=>$answer_form]);
}
//else{
//    //если юзер-админ и тикет закрыт, то ЛИШ он может его открыть
//    if($model->status==\app\models\Tickets::STATUS_CLOSE && Yii::$app->user->identity->isAdmin())
//    {
//       // echo $this->render('_open_ticket', ['model'=>$model]);
//    }
//}
//форма для открытия закрытого тикета в системе
if($model->status==\app\models\Tickets::STATUS_CLOSE){
    echo $this->render('@app/modules/ticket/views/admin/_open_form',['model'=>$answer_form]);
}