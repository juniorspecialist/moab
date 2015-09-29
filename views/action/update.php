<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Action */

$this->title = 'Редактировать акцию: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Акции', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="action-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
