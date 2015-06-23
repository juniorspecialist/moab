<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.04.15
 * Time: 14:02
 */
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;

$fieldOptions1 = [
'options' => ['class' => 'form-group has-feedback'],
'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
'options' => ['class' => 'form-group has-feedback'],
'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="register-box">
<!--    <div class="register-logo">-->
<!--        <strong>Регистрация</strong>-->
<!--    </div>-->
    <!-- /.login-logo -->
    <div class="register-box-body">


                <div class="user-default-signup">
                    <h1><?= Html::encode($this->title) ?></h1>

                    <p>Заполните поля для регистрации:</p>

                    <div class="row">

                            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                            <?= $form->field($model, 'email') ?>
                            <?= $form->field($model, 'password')->passwordInput() ?>
                            <?= $form->field($model, 'password_repeat')->passwordInput() ?>

                            <?php
                            /*$form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                'captchaAction' => '/user/default/captcha',
                                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                            ])*/
                            ?>
                            <div class="form-group">
                                <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>

                    </div>
                </div>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->