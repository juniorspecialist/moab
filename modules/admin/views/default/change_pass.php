<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.06.15
 * Time: 23:03
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Обновление пароля пользователя';

?>


<div class="addbalance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'new_password')->textInput(['maxlength' => true]) ?>

    <?=$form->field($model,'user_id')->hiddenInput()->label('Пользователь : '.$user->email)?>


    <div class="form-group">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>