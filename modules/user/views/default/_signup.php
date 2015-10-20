<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.07.15
 * Time: 10:01
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<?php $form = ActiveForm::begin(['id' => 'form-signup','action'=>'/signup']); ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordInput() ?>
<?= $form->field($model, 'password_repeat')->passwordInput() ?>

<?= $form->field($model, 'promo') ?>

<?= Html::checkbox('accept_subscribe', true,['label'=>'Подписаться на рассылку от moab.pro']) ?>

    <div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
<?php ActiveForm::end(); ?>