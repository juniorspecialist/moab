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

$this->title = 'Зарегистрироваться';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="login-box">
<!--    <div class="register-logo">-->
<!--        <strong>Регистрация</strong>-->
<!--    </div>-->
    <!-- /.login-logo -->
    <div class="login-box-body">


<!--                <div class="user-default-signup">-->


                    <div class="row" id="signup-row">

                        <?php

                            echo $this->render('_signup',['model'=>$model])

                        ?>

                    </div>

<!--                </div>-->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->