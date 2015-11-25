<?php
use yii\bootstrap\Nav;
use app\components\widgets\UserMenuWidget;

/*
$checkController = function ($route) {
    echo '<span style="display:none">'.$route.'|'.$this->context->getUniqueId().'</span><br>';
    return $route === $this->context->getUniqueId();
};*/


//$checkController = function ($route) {
//    return $route === Yii::$app->controller->id.'/'.$this->context->action->id/*$this->context->getUniqueId()*/;
//};

if(!Yii::$app->user->isGuest){
?>

<aside class="main-sidebar">

    <section class="sidebar">
        <?php
        echo UserMenuWidget::widget([]);
        ?>
        <?=\yii\helpers\Html::img('/img/moab_lk.gif')?>
    </section>
</aside>
<?php
}
?>
<style>
    .fa-tasks{
        margin-left: 10px;
    }
</style>
