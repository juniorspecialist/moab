<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 11:05
 */

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;

$this->title = 'Добавить выборку';

/* @var $this yii\web\View */
/* @var $model app\models\Selections */
/* @var $form ActiveForm */
?>


<div class="row" style="padding-bottom:30px;">

    <div class="col-md-12">

        <?php $form = ActiveForm::begin(['id'=>'metrika-form',    'fieldConfig' => ['template' => "{label}\n{input}"]/*,'enableAjaxValidation'=>true, 'enableClientValidation'=>true*/]); ?>
            <h3 style="margin-bottom:20px;"> <span class="label label-danger">Исходные фразы</span></h3>

            <div class="form-group form-inline">

                <label  class="control-label"  for="suggestform-category_id">Группа</label>

               <?=$form->field($model, 'category_id',['inline' => true])
                ->dropDownList(
                    \yii\helpers\ArrayHelper::map(
                        \app\models\Category::find()
                            ->select(['id','title'])
                            ->where(['user_id'=>Yii::$app->user->id])
                            ->orderBy('id DESC')
                            ->all()
                        ,'id','title'
                    )
                )->label(false);?>
            </div>

            <?php
            //Label для поля «Исходная ключевая фраза»
            echo $form->field($model, 'source_phrase')
                ->textarea(['cols'=>5,'rows'=>15,'onKeyUp'=>'countLines(this)'])
                ->label("Добавьте одну или несколько ключевых фраз, по которым будет осуществляться выборка (не более ".Yii::$app->user->identity->suggest_limit_words." фраз):");
            ?>

            <div class="text-right">
                <button type="text" class="btn btn-info">
                    <i class="fa fa-file-o"></i> Импорт из .txt
                </button>
                <button type="text" class="btn btn-success">
                    <i class="fa fa-table"></i> Импорт из .csv
                </button>
            </div>

            <div class="form-group form-inline">
                <?=$form->field($model, 'wordstat_syntax')->dropDownList(\app\models\Selections::getWordsStatSyntax(),[]);?>
            </div>

            <div class="form-group form-inline">

                <label class="control-label">
                    <h3> <span class="label label-danger">Частотность Wordstat</span></h3>
                </label>

                <?=$form->field($model, 'wordstat_from')->textInput( [ 'class'=>'wordstat form-control', 'type'=>'number','min'=>1,'max'=>100000000,'value'=>1, 'style'=>'width:100%'])->label(false);?>

                <?=$form->field($model, 'wordstat_to')->textInput([ 'class'=>'wordstat form-control', 'type'=>'number','min'=>1,'max'=>100000000,'value'=>100000000, 'style'=>'width:100%'])->label(false);?>

            </div>

            <p class="text-left">
                <a class="btn btn-warning" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
                    <i class="fa fa-plus"></i> Добавить минус-слова
                </a>
            </p>

            <div class="collapse" id="collapse1">
                <?php
                    echo $form->field($model, 'stop_words')
                    ->textarea(['cols'=>5, 'rows'=>15, 'onKeyUp'=>'countLinesMinus(this)'])
                    ->label('Добавьте минус-слова, ключевые фразы с которыми не должны присутствовать в выборке (не более '.Yii::$app->user->identity->suggest_limit_stop_words.' минус-слов):');
                ?>
                <br>
                Строк: <span id="source_phrase_count_minus">0</span>
            </div>

            <p class="text-left">
                <a class="btn btn-warning" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                    <i class="fa fa-plus"></i> Позиция подсказки и длина фразы
                </a>
            </p>
        <?php
        if($model->potential_traffic !== \app\models\Selections::POTENCIAL_TRAFFIC_USER){$extra_options = ['readonly'=>'readonly'];}
        ?>





            <div class="collapse" id="collapse2" id="extra_options">
                <div class="form-group form-inline field-suggestform-potential_traffic required">
                    <label class="control-label" style="width: 300px"  for="suggestform-potential_traffic">Потенциальный траффик</label>
                    <?=$form->field($model, 'potential_traffic')->dropDownList(\app\models\Selections::getPotencialTraffic())->label(false);?>
                </div>


                <div class="form-inline form-group">
                    <span class=" field-suggestform-source_words_count_from required">
                        <label class="control-label" style="width: 300px" for="suggestform-source_words_count_from">Количество слов в исходной фразе от<br></label>
                        <?=$form->field($model,'source_words_count_from')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['type'=>'number','min'=>1,'class'=>'extra_options  form-control', 'style'=>'width:100%']))->label(false);?>
                    </span>

                    <span class=" field-suggestform-source_words_count_to required">
                        <label class="control-label"  for="suggestform-source_words_count_to"> до </label>
                        <?=$form->field($model,'source_words_count_to')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['type'=>'number','min'=>1,'class'=>'extra_options  form-control', 'style'=>'width:100%']))->label(false);?>
                    </span>
                </div>

                <div class="form-inline form-group">
                    <span class=" field-suggestform-position_from required">
                        <label class="control-label"  style="width: 300px" for="suggestform-position_from">Позиция подсказки от</label>
                        <?=$form->field($model,'position_from')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['type'=>'number','min'=>1,'class'=>'extra_options form-control', 'style'=>'width:100%']))->label(false);?>
                    </span>
                    <span class=" field-suggestform-position_to required">
                        <label class="control-label" for="suggestform-position_to"> до </label>
                        <?=$form->field($model,'position_to')->textInput(\yii\helpers\ArrayHelper::merge($extra_options,['type'=>'number','min'=>1,'class'=>'extra_options form-control', 'style'=>'width:100%']))->label(false);?>
                    </span>
                </div>

                <div class="form-inline form-group">
                    <span class=" field-suggestform-suggest_words_count_from required">
                        <label class="control-label" style="width: 300px" for="suggestform-suggest_words_count_from">Количество слов в подсказке от</label>
                        <?=$form->field($model,'suggest_words_count_from')->textInput(['type'=>'number','min'=>1,'class'=>'other_extra_options form-control', 'style'=>'width:100%'])->label(false);?>
                    </span>
                    <span class=" field-suggestform-suggest_words_count_to required">
                        <label class="control-label"  for="suggestform-suggest_words_count_to"> до </label>
                        <?=$form->field($model,'suggest_words_count_to')->textInput(['type'=>'number','min'=>1,'class'=>'other_extra_options form-control', 'style'=>'width:100%'])->label(false);?>
                    </span>
                </div>


                <div class="form-inline form-group ">
                    <span class="field-suggestform-length_from required">
                        <label class="control-label" style="width: 300px" for="suggestform-length_from">Количество символов в подсказке от</label>
                        <?=$form->field($model,'length_from')->textInput(['type'=>'number','min'=>1,'class'=>'other_extra_options form-control', 'style'=>'width:100%'])->label(false);?>
                    </span>
                    <span class="field-suggestform-length_to required">
                        <label class="control-label" for="suggestform-length_to"> до </label>
                        <?=$form->field($model,'length_to')->textInput(['type'=>'number','min'=>1,'class'=>'other_extra_options form-control', 'style'=>'width:100%'])->label(false);?>
                    </span>
                </div>

            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-search"></i> Поиск', ['class' => 'btn btn-primary btn-lg', 'id'=>'suggest-submite']) ?>
            </div>
        <?php ActiveForm::end(); ?>


    </div></div>

<!-- modules-user-views-create -->

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


$this->registerJs("
$(document).ready(function(){
    $('a[data-toggle=tab]').on('shown.bs.tab', function (e) {
      //прячем кнопку, если активна не первая вкладка
      if($(e.target).attr('id')=='initial_phrase'){
        $('#suggest-submite').show();
      }else{
        $('#suggest-submite').hide();
      }
    });
});", \yii\web\View::POS_READY);

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
