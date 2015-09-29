<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">MOAB.pro</span><span class="logo-lg">MOAB.pro</span>', ['http://moab.pro'], ['class' => 'logo','target'=>'_blank']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">


                <li>
                    <a href="/profile" class="dropdown-toggle" >
                    <span class="hidden-xs"><?=Html::encode(\Yii::$app->session->get('user.email'));?></span>
                    </a>
                </li>

                <li>
                    <a href="/financy" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">Баланс : <?=Yii::$app->formatter->asInteger(\Yii::$app->user->identity->balance);?> руб.</span>
                    </a>
                </li>

                <li>
                    <?= Html::a(
                        '<span class="hidden-xs">Пополнить баланс</span>',
                        ['/pay'],
                        [/*'data-method' => 'post',*/ 'class' => 'dropdown-toggle'/*, 'data-toggle'=>'dropdown'*/]
                    ) ?>
                </li>

                <li>
                    <?= Html::a(
                        '<span class="hidden-xs">Выход</span>',
                        ['/site/logout'],
                        ['data-method' => 'post', 'class' => 'dropdown-toggle', 'data-toggle'=>'dropdown']
                    ) ?>
                </li>





                <!-- User Account: style can be found in dropdown.less -->
<!--                <li>-->
<!--                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
<!--                </li>-->
            </ul>
        </div>
    </nav>
</header>
