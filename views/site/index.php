<?php
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">

        <p><a class="btn btn-lg btn-success" href="<?=Yii::$app->urlManager->createAbsoluteUrl(['user/default/login'])?>">Авторизация</a></p>

        <p><a class="btn btn-lg btn-info" href="<?=Yii::$app->urlManager->createAbsoluteUrl(['user/default/signup'])?>">Регистрация</a></p>
    </div>

</div>
