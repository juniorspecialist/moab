<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zhuravljov\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Base */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'one_month_price')->textInput() ?>

    <?= $form->field($model, 'one_month_user_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'three_month_price')->textInput() ?>

    <?= $form->field($model, 'three_month_user_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'six_month_price')->textInput() ?>

    <?= $form->field($model, 'six_month_user_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'twelfth_month_price')->textInput() ?>

    <?= $form->field($model, 'twelfth_month_user_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eternal_period_price')->textInput() ?>

    <?= $form->field($model, 'eternal_period_user_info')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'enabled_user')->checkbox() ?>


    <?= $form->field($model, 'last_update')->textInput() ?>
    <?= $form->field($model, 'next_update')->textInput() ?>
    <?= $form->field($model, 'count_keywords')->textInput() ?>
    <?= $form->field($model, 'add_in_update')->textInput() ?>


    <?=$form->field($model, 'hidebases')->checkboxList(\yii\helpers\ArrayHelper::map(($model->isNewRecord)?\app\models\Base::find()->all():\app\models\Base::find()->where(['!=','id',$model->id])->all(),'id','title'))->label('Если пользователь будет подписан на тек. базу, то какие подписки необходимо для него спрятать')?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
