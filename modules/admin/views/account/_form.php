<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Base */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="base-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pass')->textInput() ?>

    <?= $form->field($model, 'server')->textInput(['maxlength' => true]) ?>

    <?php

    if($model->user){
        //echo $form->field($model, 'user_id')->textInput(['maxlength' => true]);
        echo '<strong>Пользователь - '.$model->user->email.'</strong><br>';
    }

    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
