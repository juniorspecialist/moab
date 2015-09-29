<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.04.15
 * Time: 14:00
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\PasswordResetRequestForm */
$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="register-box" id="register-form">
    <!-- /.login-logo -->
    <div class="register-box-body">
<!--        <div class="site-request-password-reset">-->
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Пожалуйста укажите вашу почту. Ссылка на сброс пароля будет выслана вам на почту.</p>

            <div class="row">
                
                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                    <?= $form->field($model, 'email')->label(false)->textInput(['placeholder' => 'E-mail']) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                
            </div>

        <?= Html::a('Войти', ['/']) ?>
        <br>
        <?= Html::a('Зарегистрироваться', ['/']) ?>

        <!--        </div>-->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->