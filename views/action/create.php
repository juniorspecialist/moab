<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Action */

$this->title = 'Добавить акцию';
$this->params['breadcrumbs'][] = ['label' => 'Акции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
