<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.06.15
 * Time: 21:11
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Пополнение баланса пользователя';

?>


<div class="addbalance-form">

    <?php $form = ActiveForm::begin(); ?>

<?php //echo $form->errorSummary($model); ?>

<?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model,'user_id')->hiddenInput()->label('Пользователь : '.$user->email)?>


<div class="form-group">
    <?= Html::submitButton('Пополнить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>