<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.04.15
 * Time: 13:50
 */

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = 'Профиль пользователя:'.$model->email;
$this->params['breadcrumbs'][] = ['label' => 'Профиль'];

?>
<div class="profil-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label'=>'Дата регистрации',
                'value'=>date('Y-m-d H:i:s', $model->created_at),
            ],
            [
                'label'=>'Дата последнего входа',
                'value'=>date('Y-m-d H:i:s', $model->authLogLast->create_at),
            ],
            [
                'label'=>'IP-адрес последнего входа',
                'value'=>$model->authLogLast->ip,
            ],
            [
                'label'=>'API-ключ',
                'value'=>$model->api_key,
            ],

            [
                'label'=>'Доступы:',
                'value'=>'',
            ],

            [
                'label'=>'Сервер',
                'value'=>$model->accessServer,
            ],

            [
                'label'=>'Логин',
                'value'=>$model->accessLogin,
            ],

            [
                'label'=>'Пароль',
                'value'=>$model->accessPass,
            ],
        ],
    ]) ?>

</div>
