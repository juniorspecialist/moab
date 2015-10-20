<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.15
 * Time: 14:37
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Base */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Создать тикет';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prioritet')->inline()->radioList($model->getPrioritetList()); ?>

    <?= $form->field($model, 'question')->textarea(['rows' => 10, 'cols'=>10]) ?>

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>