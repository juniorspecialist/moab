<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.08.15
 * Time: 16:36
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Base */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(['id' => 'answer_form']);?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>

    <?php //echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'ticket_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'msg')->textarea(['rows' => 5, 'cols'=>5])->label('Текст сообщения') ?>

    <div class="form-group">
        <?= Html::submitButton('Ответить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?php $formClose = ActiveForm::begin(['options' => ['data-pjax' => true ], 'action'=>['close','id'=>$model->ticket_id]]); ?>

    <?= $formClose->field($model, 'ticket_id')->hiddenInput()->label(false) ?>

    <?= Html::submitButton('Закрыть тикет', ['class' => 'btn btn-primary', 'style'=>'float:right; margin-top:-50px;', 'data-confirm' => 'Вы уверены, что хотите закрыть тикет ?']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php Pjax::end();



$this->registerJs(
    '$("document").ready(function(){
        $("#answer_form").on("pjax:end", function() {
            $.pjax.reload({container:"#answers_ticket"});  //Reload answers
        });
    });'
);