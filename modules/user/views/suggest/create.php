<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 11:05
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

$this->title = 'Добавить выборку';

/* @var $this yii\web\View */
/* @var $model app\models\Selections */
/* @var $form ActiveForm */
?>
<div class="modules-user-views-create">

    <?php $form = ActiveForm::begin(['id'=>'metrika-form',    'fieldConfig' => ['template' => "{label}\n{input}"]/*,'enableAjaxValidation'=>true, 'enableClientValidation'=>true*/]); ?>

    <?= $form->errorSummary($model); ?>

    <?php

    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Исходные фразы',
                'content' => $this->render('initial_phrase',['model'=>$model, 'form'=>$form]),
                'active' => true
            ],
            [
                'label' => 'Минус-слова',
                'content' => $this->render('stop_words',['model'=>$model, 'form'=>$form]),
            ],
            [
                'label' => 'Дополнительные параметры',
                'content' => $this->render('extra_options',['model'=>$model, 'form'=>$form]),
                'options' => ['id' => 'extra_options'],
            ],
            [
                'label' => 'Параметры Wordstat',
                'content' => $this->render('wordstat_options',['model'=>$model, 'form'=>$form]),
            ],

        ],
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить выборку', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- modules-user-views-create -->

<style>
    #selections-need_wordstat, #selections-potential_traffic, #selections-category_id{
        width: 400px;
    }
</style>

<?php
//регистрируем скрипт для выбора числовых значений в удобной форме
//$this->registerJsFile('/js/jquery.fs.stepper.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerJs('$(document).ready(function(){
//    $("input.extra_options, input.other_extra_options, input.wordstat").stepper();
//    //$(".extra_options").parent("div.stepper").addClass("disabled");
//});', \yii\web\View::POS_READY);
//ограничение на ввод кол-ва значений в поля textarea

//$this->registerCssFile('/css/jquery.fs.stepper.css');
?>
<style>
    input[type="text"], input[type="number"] {
        position: relative;
        /*margin: 0 0 1rem;*/
        border: 1px solid #BBB;
        border-color: #BBB #ECECEC #ECECEC #BBB;
        padding: .2rem;
        width: 300px;
    }

    /* Spin Buttons modified */
    input[type="text"].mod::-webkit-outer-spin-button,
    input[type="number"].mod::-webkit-outer-spin-button,
    input[type="text"].mod::-webkit-inner-spin-button,
    input[type="number"].mod::-webkit-inner-spin-button{
        -webkit-appearance: none;
        background: #FFF url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAAKUlEQVQYlWNgwAT/sYhhKPiPT+F/LJgEsHv37v+EMGkmkuImoh2NoQAANlcun/q4OoYAAAAASUVORK5CYII=) no-repeat center center;
        width: 1em;
        border-left: 1px solid #BBB;
        opacity: .5; /* shows Spin Buttons per default (Chrome >= 39) */
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
    }
    input[type="text"].mod::-webkit-inner-spin-button:hover,
    input[type="text"].mod::-webkit-inner-spin-button:active,
    input[type="number"].mod::-webkit-inner-spin-button:hover,
    input[type="number"].mod::-webkit-inner-spin-button:active{
        box-shadow: 0 0 2px #0CF;
        opacity: .8;
    }

    /* Override browser form filling */
    input:-webkit-autofill {
        background: black;
        color: red;
    }
    .form-group{
        width: auto;
        margin-top: 10px;
    }
    .form-inline .form-group {
        width: 300px;
    }
</style>