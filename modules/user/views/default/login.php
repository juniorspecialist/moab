<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.04.15
 * Time: 13:58
 */
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Авторизация';


?>

<div class="login-box">
    <div class="login-logo">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
<!--        <p class="login-box-msg">Sign in to start your session</p>-->

        <div id="div-login">
            <?php
                echo $this->render('_login', ['model'=>$model]);
            ?>
        </div>

        <br/>
        <?= Html::a('Забыли пароль ?', ['request-password-reset']) ?>


    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->