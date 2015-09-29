<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.07.15
 * Time: 16:38
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование интервала подписок пользователя';

?>


<div class="update-subscribe-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->errorSummary($model); ?>

    <?php

    echo $form->field($model, 'from')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', [
        //'phpDatetimeFormat' => 'yyyy-MM-dd\'T\'HH:mm:ssZZZZZ',
        'clientOptions' => [
            //'minDate' => new \yii\web\JsExpression('new Date("2015-01-01")'),
            'sideBySide' => true,
            'widgetPositioning' => [
                'horizontal' => 'auto',
                'vertical' => 'auto',
            ]
        ]
    ]);
    ?>


    <?php

    echo $form->field($model, 'to')->widget('trntv\yii\datetimepicker\DatetimepickerWidget', [
        //'phpDatetimeFormat' => 'yyyy-MM-dd\'T\'HH:mm:ssZZZZZ',
        //'phpDatetimeFormat' => 'dd-MM-yyyy\'T\'HH:mm:ssZZZZZ',
        'clientOptions' => [
            //'minDate' => new \yii\web\JsExpression('new Date("2015-01-01")'),
            'sideBySide' => true,
            'widgetPositioning' => [
                'horizontal' => 'auto',
                'vertical' => 'auto',
            ]
        ]
    ]);
    ?>


    <div class="form-group">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>