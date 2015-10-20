<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Base */

$this->title = 'Редактировать Базу: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="base-update">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
