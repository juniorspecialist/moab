<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.04.15
 * Time: 13:50
 */

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = 'Профиль пользователя: '.$model->email;
$this->params['breadcrumbs'][] = ['label' => 'Профиль'];

?>
<div class="profil-view">

    <?=Html::a('<img align="center" width="100%" src="/img/Baner3.gif" style="padding:15px 0px;">', 'http://moab.pro', ['target'=>'_blank'])?>

    <?= DetailView::widget([
        'model' => $model,
        'template'=>function ($attribute, $index, $widget){
            if($index==0 || $index==5 ||$index==9){
                return "<tr class='danger'><th>".$attribute['label']."</th><td>".$attribute['value']."</td></tr>";
            }else{
                return "<tr><th>".$attribute['label']."</th><td>".$attribute['value']."</td></tr>";
            }
        },
        'attributes' => [
            [
                'label'=>'Общая информация',
                'value'=>'',
            ],
            [
                'label'=>'Дата регистрации',
                'value'=>date('d-m-Y H:i:s', $model->created_at),
            ],
            [
                'label'=>'Дата последнего входа',
                'value'=>date('d-m-Y H:i:s', $model->authLogLast->create_at),
            ],
            [
                'label'=>'IP-адрес последнего входа',
                'value'=>$model->authLogLast->ip,
            ],
        ],
    ]) ?>

</div>
