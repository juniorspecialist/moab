<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Выбор способа пополнения';

?>

<style>
    img.pay_img{
        width: 200px;
        margin-top: 30px;
        /*border: 1px solid #ffffff;*/
    }
</style>

<div class="pay-default-index">
    <p>
        <?php
            echo Html::a(Html::img('/img/robokassa.png',['class'=>'pay_img']),['/pay/robokassa/index'], ['alt'=>'Пополнение через Робокассу', 'class'=>'btn btn-default2']);
        ?>
    </p>

<!--    <p>-->
        <?php
        //echo Html::a(Html::img('/img/wmlogo_vector_blue.png',['class'=>'pay_img']),['/pay/webmoney/index'], ['class'=>'']);
        ?>
<!--    </p>-->


    <?php

    $fieldOptions1 = [
        //'options' => ['class' => 'form-group has-feedback'],
        'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
    ];

    ?>

    <?php $form = ActiveForm::begin(['id' => 'promo-form']); ?>

    <?= $form
        ->field($model, 'promo', $fieldOptions1)
        ->label('Активировать промокод')
        ->textInput(['placeholder' => $model->getAttributeLabel('promo')]) ?>

    <div class="row">
        <div class="col-xs-4">
            <?= Html::submitButton('Активация', ['class' => 'btn btn-primary btn-flat', 'name' => 'login-button']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>




</div>
