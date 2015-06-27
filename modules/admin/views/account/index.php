<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.15
 * Time: 13:43
 */


use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аккаунты (RDP)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-index">

    <p>
        <?= Html::a('Загрузка', ['upload'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'SERVER',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->server;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Login',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->login;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Password',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->pass;
                },
            ],

            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Пользователь',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data->userEmail;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}{delete}'
            ],

        ],
    ]); ?>

</div>