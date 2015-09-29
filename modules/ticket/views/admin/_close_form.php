<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.15
 * Time: 16:15
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

        <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ], 'action'=>['close','id'=>$model->id]]); ?>

        <?php //echo $form->errorSummary($model); ?>

        <?= $form->field($model, 'ticket_id')->hiddenInput()->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Закрыть тикет', ['class' => 'btn btn-primary', 'style'=>'float:right']) ?>
        </div>

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