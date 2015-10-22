<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 10:45
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$this->registerJs(
    '$("document").ready(function(){
        $("#new_category").on("pjax:end", function(e) {
            //$.pjax.reload({container:"#categorys"});  //Reload GridView
            //e.preventDefault();
        });
    });'
);
?>

<?php //yii\widgets\Pjax::begin(['id' => 'new_category']) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form-category',
        'action'=>'/user/category/index',
        //'layout' => 'horizontal',
        //['data-pjax' => true ],
        'options' => [
            'class' => 'form-horizontal',
            'style'=>'width: 550px;height: 40px;',
            //['data-pjax' => 1 ],
        ],
        'fieldConfig' => [
            'template' => "<div class=\"col-md-10\">{input}</div>\n<div class=\"col-md-offset-2 col-md-10\">{error}</div>",
        ],
    ]); ?>

    <div class="row" style="width: 450px;float: left;height: 40px;margin-left: 10px">
        <?= $form->field($model, 'title', [
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('title'),
                'style'=>'width:370px; /*margin-left:-60px*/'
            ],
        ])/*->inline(true)*/
            ->label(false) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php //yii\widgets\Pjax::end() ?>

<script>

</script>

