<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

<meta http-equiv="x-ua-compatible" content="IE=9" >

    <?= Html::a('<span class="logo-mini"><img src="/img/nm50.png" width="25px"></span><span class="logo-lg">MOAB.pro</span>', 'http://moab.pro', ['class' => 'logo','target'=>'_blank']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

<!--        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">-->
<!--            <span class="sr-only">Toggle navigation</span>-->
<!--        </a>-->

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li>
                    <?php 
                   echo  Html::button(
                        '<i class="fa fa-line-chart"></i>  Увеличить семантику в 3 раза',
                        ['data-toggle'=>'modal','data-target'=>'#zakazFormModal', 'class'=>'btn btn-success2', 'style'=>'margin-top:5px']
                    ) ?>
                </li>

                <li>
                    <a href="/profile" class="dropdown-toggle" >
                    <span class="hidden-xs"><i class="fa fa-user"></i> <?=Html::encode(\Yii::$app->session->get('user.email'));?></span>
                    </a>
                </li>

                <li>
                    <a href="/financy" class="dropdown-toggle" >
                        <span class="hidden-xs">Баланс : <?=Yii::$app->formatter->asInteger(\Yii::$app->user->identity->balance);?> <i class="fa fa-rouble"></i> </span>
                    </a>
                </li>

                <li>
                    <?php
                    echo Html::a(
                        '<span class="hidden-xs">Пополнить баланс</span>',
                        ['/pay'],
                        [ 'class' => 'dropdown-toggle']
                    )
                    ?>
                </li>

                <li>
                    <?= Html::a(
                        '<span class="hidden-xs"><i class="fa fa-sign-out"></i> Выход</span>',
                        ['/site/logout'],
                        ['data-method' => 'post', 'class' => 'dropdown-toggle', 'data-toggle'=>'dropdown']
                    ) ?>
                </li>


            </ul>
        </div>
    </nav>
</header>

<?php

\yii\bootstrap\Modal::begin([
    'options'=>[
        'style'=>'display:none',
    ],
    'header'=>'<h4>Форма заказа</h4>',
    'id'=>'zakazFormModal',
    'toggleButton' => [
        //'label' => !$subs->isNewRecord ? 'Продлить' : 'Подписаться',
        //'tag'=>'button',
        //'class'=>'btn btn-primary',
        'style'=>'display:none',
    ],

]);
?>
<form method="post" class="ajax_form" role="form" id="form-moab-pro" data-message="zakaz2UsAlert" >
    <div class="col-sm-12">
                <div class="alert alert-success alert-dismissable" style="display: none;" id="zakaz2UsAlert">
                    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
Спасибо! Ваше сообщение отправлено.
                </div>
            </div>
    <p style="text-align:left">Пользуясь базой MOAB Suggest, вы сможете добиться значительного увеличения вашей семантики. Однако, если вы ставите перед собой действительно амбициозные задачи, вам необходима база MOAB Pro.</p>
<p style="font-weight:bold;" class="coral" >Дополнительно вы получите</p>
<div class="text-icon">
<span class="icon-ar ">
<i class="fa fa-2x fa-database"></i>
</span>
<div class="text-icon-content">
<p  style="text-align:left">3,2 млрд эксклюзивных ключевых слов из Яндекс.Метрики с возможностью выборки по конкурентам</p>
</div>
</div>

<div class="text-icon">
<span class="icon-ar ">
<i class="fa fa-2x fa-graduation-cap"></i>
</span>
<div class="text-icon-content">
<p  style="text-align:left">8-часовой индивидуальный тренинг с экспертом MOAB</p>
</div>
</div>

<div class="text-icon">
<span class="icon-ar ">
<i class="fa fa-2x fa-user-plus"></i>
</span>
<div class="text-icon-content">
<p style="text-align:left">2 аккаунта вместо одного</p>
</div>
</div>

<div class="text-icon">
<span class="icon-ar ">
<i class="fa fa-2x fa-gift"></i>
</span>
<div class="text-icon-content">
<p style="text-align:left">пожизненную подписку на MOAB Pro c вечными бесплатными обновлениями</p>
</div>
</div>

<div class="text-icon">
<span class="icon-ar ">
<i class="fa fa-2x fa-shopping-cart"></i>
</span>
<div class="text-icon-content">
<p style="text-align:left">бесплатную пожизненную подписку на все будущие продукты под брендом MOAB</p>
</div>
</div>

    <input type="hidden" value="zakazForm2" name="zakazForm2">
    <input type="hidden" id="m_pagetitle" value="MOAB BASE" name="pagetitle">

    <div id="sel_tarif"> </div>
    <p class="coral"  style="font-weight:bold;">Контактные данные</p>
    <div class="form-group">
        <input class="form-control validate[required] text-input" placeholder="Имя *" id="name" name="name">
    </div>
    <div class="form-group">
        <input type="email" class="form-control validate[required,custom[email]]"  placeholder="E-MAIL *" id="email" name="email">
    </div>
    <div class="form-group">
        <input class="form-control" placeholder="Организация" id="company" name="company">
    </div>
    <div class="form-group">
        <input class="form-control" placeholder="Должность" id="position" name="position">
    </div>


    <div class="form-group">
        <textarea class="form-control" placeholder="Комментарий" name="message" id="message"></textarea>
    </div>
    <p class="coral"  style="font-weight:bold;">Введите промокод для получения скидки или подарка</p>
    <div class="form-group">
        <input class="form-control" placeholder="Промокод" id="promo" name="promo">
    </div>
   <p class="coral">Заказ тестовой выборки БЕСПЛАТНО</p>
    <p>Введите до 3 важных для Вас ключевых слов. Мы бесплатно пришлем вам выборки из MOAB Pro и MOAB Suggest по этим запросам, чтобы вы могли убедиться в качестве и количестве ключевых слов в нашей базе</p>
        <div class="form-group">
        <input class="form-control" d="" placeholder="Ключевые слова" id="test" name="test">
    </div>

    <div class="checkbox">                  
        <label><input type="checkbox" name="news" value="1" checked>Подписаться на рассылку от moab.pro</label>
    </div>

    <div class="form-group">
        <center><button type="submit" class="btn btn-primary " id="submitbtn">Отправить</button></center>
    </div>


</form>
<?php
\yii\bootstrap\Modal::end();
?>
