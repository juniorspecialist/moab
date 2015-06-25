<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.15
 * Time: 13:37
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$this->title = 'Пакетная загрузка аккаунтов (RDP)';

$this->params['breadcrumbs'][] = $this->title;

?>

<p>Заполните данные для загрузки:</p>

<div class="base-form">

    <?php $form = ActiveForm::begin(['id' => 'form-upload']); ?>

    <p>формат данных:login;password;server</p>

    <?php echo $form->errorSummary($model); ?>

    <?= $form->field($model, 'upload')->textarea(['cols'=>5, 'rows'=>10]) ?>

    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>