<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.08.15
 * Time: 16:53
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Документы';
//$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$show_button = false;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?php echo $form->errorSummary($model)?>

    <?php  $form->field($model, 'passport')->fileInput() ?>

    <?php
    if($model->showeField(\app\models\Docs::TYPE_DOC_PASSPORT,$dataProvider->getModels()))
    {
        $show_button = true;
        echo $form->field($model, 'passport')->fileInput()->label($model->getAttributeLabel('passport'),['style'=>'float:left']);
    }
    ?>

    <?php
        if($model->showeField(\app\models\Docs::TYPE_DOC_PHOTO_WITH_PASS,$dataProvider->getModels()))
        {
            $show_button = true;
            echo $form->field($model, 'passport_in_hands')->fileInput()->label($model->getAttributeLabel('passport_in_hands'),['style'=>'float:left']);
        }
    ?>

    <?php
        //$form->field($model, 'drive_passport')->fileInput()
        if($model->showeField(\app\models\Docs::TYPE_DOC_DRIVER,$dataProvider->getModels()))
        {
            $show_button = true;
            echo $form->field($model, 'drive_passport')->fileInput()->label($model->getAttributeLabel('drive_passport'),['style'=>'float:left']);
        }
    ?>

    <?php
        if($show_button){
            echo \yii\helpers\Html::submitButton('Загрузить', ['class'=>'btn btn-success']);
        }
    ?>

<?php ActiveForm::end() ?>

<br>
<div class="base-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'=>false,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Документ',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::a($data->type,['modal', 'id'=>$data->id], ['class'=>'modal_photo']);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Статус',
                'format'=>'raw',
                'value' => function ($data) {
                    //на рассмотрении – желтый, принят – зеленый, отклонен – красный
                    if($data->status==\app\models\Docs::STATUS_PROCESS){$class = 'label label-warning'; }
                    if($data->status==\app\models\Docs::STATUS_ACCEPT){  $class = 'label label-success'; }
                    if($data->status==\app\models\Docs::STATUS_CANCEL){ $class = 'label label-danger'; }

                    return Html::tag('span',$data->statusText,  ['class'=>$class]);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Дата загрузки',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::a(date('d-m-Y H:i:s',$data->uploaded),['modal', 'id'=>$data->id], ['class'=>'modal_photo','alt_id'=>$data->id]);
                },
            ],
        ],
    ]); ?>

</div>

<?php

\yii\bootstrap\Modal::begin([
    'header' => false,
    //'size'=>\yii\bootstrap\Modal::SIZE_LARGE,
    'toggleButton' => [
        //'label' => !$subs->isNewRecord ? 'Продлить' : 'Подписаться',
        //'tag'=>'button',
        //'class'=>'btn btn-primary',
        'style'=>'display:none',
    ],
    'id'=>'modalWinPhoto',
]);
\yii\bootstrap\Modal::end();

?>

<style>
    .modal-content {
        width: 850px;
        margin-left: -200px;
    }
</style>