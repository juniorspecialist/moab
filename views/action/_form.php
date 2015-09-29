<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use zhuravljov\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Action */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="action-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => false,]); ?>

    <?= $form->field($model, 'period_from')->widget(DateTimePicker::className(), [
        'clientOptions' => [
            'format' => 'dd.mm.yyyy hh:ii',
            'language' => 'ru',
            'autoclose' => true,
            'todayBtn' => true,
            'minuteStep'=> 5,
        ],
        'clientEvents' => [],
    ]) ?>


    <?= $form->field($model, 'period_to')->widget(DateTimePicker::className(), [
        'clientOptions' => [
            'format' => 'dd.mm.yyyy hh:ii',
            'language' => 'ru',
            'autoclose' => true,
            'todayBtn' => true,
            'minuteStep'=> 5,
        ],
        'clientEvents' => [],
    ]) ?>

    <?= $form->field($model, 'promo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'base_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Base::find()->all(),'id', 'title')) ?>

    <?= $form->field($model, 'subscribe_period')->dropDownList(\app\models\UserSubscription::getPeriodList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
