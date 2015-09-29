<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 16:41
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;


$form = ActiveForm::begin([
    'id' => 'popover'.$model->id,
    //'layout' => 'horizontal',
    //['data-pjax' => true ],
    'options' => [
        'class' => 'form-horizontal',
        'style'=>'width: 450px;height: 40px;display:none;',
        //['data-pjax' => 1 ],
    ],
    'fieldConfig' => [
        'template' => "<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
    ],
]); ?>

    <div class="row" style="width: 250px;float: left;height: 40px;margin-left: 10px">
        <?= $form->field($model, 'title', [
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('title'),
                'style'=>'width:170px; /*margin-left:-60px*/'
            ],
        ])/*->inline(true)*/
        ->label(false) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary btn-xs', 'name' => 'signup-button']) ?>
    </div>

<?php ActiveForm::end(); ?>