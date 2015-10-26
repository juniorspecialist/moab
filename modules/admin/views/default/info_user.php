<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.09.15
 * Time: 9:24
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;


$this->title = 'Информация о пользователе: '.$user->email;

$this->params['breadcrumbs'][] = $this->title;
?>



<div class="profil-view">
    <?= DetailView::widget([
        'model' => $user,
        'attributes' => [

            [
                'label'=>'Дата регистрации',
                'value'=>date('d-m-Y H:i:s', $user->created_at),
            ],

        ],
    ]) ?>
    <?php
    echo \yii\widgets\DetailView::widget([
        'model' => $user,
        'attributes' => [
            [
                'label'=>'API-ключ',
                'format'=>'raw',
                'value'=>Html::textInput('api_key',$user->api_key, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
            ],
            [
                'label'=>'Компьютер',
                'format'=>'raw',
                'value'=>Html::textInput('access_server',$user->accessServer, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
                'visible'=>($user->accessServer!=='Нет')?true:false
            ],

            [
                'label'=>'Пользователь',
                'format'=>'raw',
                'value'=>Html::textInput('access_user',$user->accessLogin, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
                'visible'=>($user->accessServer!=='Нет')?true:false
            ],

            [
                'label'=>'Пароль',
                'format'=>'raw',
                'value'=>Html::textInput('access_pass',$user->accessPass, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
                'visible'=>($user->accessServer!=='Нет')?true:false
            ],

        ],
    ]);
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->errorSummary($model); ?>

    <?= $form->field($user, 'suggest_limit_words')->textInput(); ?>

    <?=$form->field($user,'suggest_limit_stop_words')->textInput()?>


    <div class="form-group">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>