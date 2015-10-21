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

    <?php $form = ActiveForm::begin(['id'=>'metrika-form']); ?>

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
                'label' => 'Дополнительные параметры',
                'content' => $this->render('extra_options',['model'=>$model, 'form'=>$form]),
                //'headerOptions' => [...],
                'options' => ['id' => 'extra_options'],
            ],
            [
                'label' => 'Параметры Wordstat',
                //'url' => 'http://www.example.com',
                'content' => $this->render('wordstat_options',['model'=>$model, 'form'=>$form]),
            ],
            [
                'label' => 'Минус-слова',
                'content' => $this->render('stop_words',['model'=>$model, 'form'=>$form]),
            ],
        ],
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
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
$this->registerJsFile('/js/jquery.fs.stepper.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs('$(document).ready(function(){
    $("input[type=number]").stepper();
});', \yii\web\View::POS_READY);
//ограничение на ввод кол-ва значений в поля textarea

$this->registerCssFile('/css/jquery.fs.stepper.css');
?>