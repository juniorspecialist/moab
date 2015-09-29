<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.08.15
 * Time: 16:36
 */
use yii\helpers\Html;

if(Yii::$app->user->identity->isAdmin($answer['user']['email']))
{
    $class = 'panel-info';
}else
{
    $class = 'panel-default';
}
    ?>
    <div class="panel <?=$class?>">
        <div class="panel-heading"><?=(Yii::$app->user->identity->isAdmin())?Html::a($answer['user']['email'], ['/admin/default/info-user','id'=>$answer['user']['id']], ['target'=>'_blank']):$answer['user']['email']?>  <?=date('d-m-Y H:i:s',$answer['created'])?></div>
        <div class="panel-body">
            <?=Html::encode($answer['msg'])?>
        </div>
    </div>
<?php

?>
