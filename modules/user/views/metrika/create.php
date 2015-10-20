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

/* @var $this yii\web\View */
/* @var $model app\models\Selections */
/* @var $form ActiveForm */
?>
<div class="modules-user-views-create">

    <?php $form = ActiveForm::begin(); ?>

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
                    'options' => ['id' => 'myveryownID'],
                ],
                [
                    'label' => 'Параметры Wordstat',
                    //'url' => 'http://www.example.com',
                    'content' => $this->render('wordstat_options',['model'=>$model, 'form'=>$form]),
                ],
            [
                'label' => 'Dropdown',
                'items' => [
                    [
                        'label' => 'DropdownA',
                        'content' => 'DropdownA, Anim pariatur cliche...',
                    ],
                    [
                        'label' => 'DropdownB',
                        'content' => 'DropdownB, Anim pariatur cliche...',
                    ],
                ],
            ],
        ],
    ]);

    ?>


    <?= $form->field($model, 'type') ?>
    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'results_count') ?>





    <?= $form->field($model, 'need_wordstat') ?>

    <?= $form->field($model, 'wordstat_syntax') ?>

    <?= $form->field($model, 'wordstat_from') ?>

    <?= $form->field($model, 'wordstat_to') ?>



    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- modules-user-views-create -->