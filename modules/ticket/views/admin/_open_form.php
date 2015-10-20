<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.09.15
 * Time: 9:25
 */
/*
 * форма открытия закрытого тикета
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Base */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(['id' => 'open_form']);?>

    <div class="ticket-form">

        <?php $formClose = ActiveForm::begin(['options' => ['data-pjax' => true ], 'action'=>['open','id'=>$model->ticket_id]]); ?>

        <?= $formClose->field($model, 'ticket_id')->hiddenInput()->label(false) ?>

        <?= Html::submitButton('Открыть тикет', ['class' => 'btn btn-primary', 'style'=>'', 'data-confirm' => 'Вы уверены, что хотите открыть закрытый тикет ?']) ?>

        <?php ActiveForm::end(); ?>

    </div>

<?php Pjax::end();



$this->registerJs(
    '$("document").ready(function(){
        $("#open_form").on("pjax:end", function() {
            $.pjax.reload({container:"#answers_ticket"});  //Reload answers
        });
    });'
);