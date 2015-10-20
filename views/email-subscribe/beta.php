<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.07.15
 * Time: 11:12
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Регистрация бета-тестера';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <strong>Регистрация бета-тестера</strong>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <!--        <p class="login-box-msg">Sign in to start your session</p>-->

        <?php $form = ActiveForm::begin(['id' => 'beta-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'email', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>


        <div class="row">
            <div class="col-xs-4">
                <?= Html::submitButton('Подать заявку', ['class' => 'btn btn-primary btn-flat', 'name' => 'beta-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->