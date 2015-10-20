<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.08.15
 * Time: 15:28
 */

use yii\helpers\Html;

?>
<div class="addbalance-form">

    <?=Html::beginForm(['accept'])?>

    <?=Html::hiddenInput('id','',['class'=>'photo_id'])?>

    <div class="form-group">
        <?= Html::submitButton('Принять', ['class' => 'btn btn-success','style'=>'float:left; margin-right:20px']) ?>

        <?=Html::endForm();?>

        <?=Html::beginForm(['cancel'])?>
        <?=Html::hiddenInput('id','',['class'=>'photo_id'])?>
        <?= Html::submitButton('Отклонить', ['class' => 'btn btn-danger', 'style'=>'margin-right:20px']) ?>
        <?=Html::button('Закрыть', ['data-dismiss'=>'modal', 'aria-hidden'=>true, 'class'=>'btn btn-info'])?>
        <?=Html::endForm();?>

    </div>

</div>