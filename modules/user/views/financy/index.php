<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.06.15
 * Time: 9:55
 */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Финансы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financy-index">

      <?=Html::a('<img align="center" width="100%" src="/img/Baner3.gif" style="padding:15px 0px;">', 'http://moab.pro', ['target'=>'_blank'])?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'rowOptions' => function($model, $key, $index, $grid){
                if($model->type_operation == \app\models\Financy::TYPE_OPERATION_MINUS)
                {
                    return ['class' => 'danger'];
                }else
                {
                    return ['class' => 'success'];
                }
            },
            'columns' => [
                [
                    'attribute' => 'create_at',
                    'format'=>'raw',
                    'value' => function ($data) {
                        return date('d-m-Y H:i:s',$data->create_at);
                    },
                ],
                [
                    'attribute' => 'create_at',
                    'format'=>'raw',
                    'label'=>'Вид операции',
                    'value' => function ($data) {
                        return $data->typeOperation;
                    },
                ],
                'desc',
                [
                    'format'=>'raw',
                    'attribute'=>'amount',
                    'value'=>function($data){
                        return Yii::$app->formatter->asInteger($data->amount).' <i class="fa fa-rouble"></i>';
                    }
                ],
                [
                    'format'=>'raw',
                    'attribute'=>'balance_after',
                    'value'=>function($data){
                        return Yii::$app->formatter->asInteger($data->balance_after).' <i class="fa fa-rouble"></i>';
                    }
                ],
            ],
        ]); ?>
    </div>

</div>