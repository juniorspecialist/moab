<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.07.15
 * Time: 9:55
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<?php $form = ActiveForm::begin(['id' => 'login-form','action'=>'/login']); ?>

<?= $form
    ->field($model, 'username', $fieldOptions1)
    ->label(false)
    ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

<?= $form
    ->field($model, 'password', $fieldOptions2)
    ->label(false)
    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

<div class="row">
    <!--            <div class="col-xs-8">-->
    <!--                --><?php //echo $form->field($model, 'rememberMe')->checkbox() ?>
    <!--            </div>-->
    <!-- /.col -->
    <div class="col-xs-4">
        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-flat', 'name' => 'login-button']) ?>
    </div>
    <!-- /.col -->
</div>


<?php ActiveForm::end(); ?>
