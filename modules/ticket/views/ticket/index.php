<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.08.15
 * Time: 13:46
 */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Тикеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financy-index">

    <?=Html::a('<img align="center" width="100%" src="/img/Baner3.gif" style="padding:15px 0px;">', 'http://moab.pro', ['target'=>'_blank'])?>


    <?=Html::a('Создать тикет',['create'], ['class'=>'btn btn-success'])?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary'=>false,
            //'layout'=>'{summary}\n{items}\n{pager}',
            //'filterModel' => $searchModel,
//            'rowOptions' => function($model, $key, $index, $grid){
//                if($model->type_operation == \app\models\Financy::TYPE_OPERATION_MINUS)
//                {
//                    return ['class' => 'danger'];
//                }else
//                {
//                    return ['class' => 'success'];
//                }
//            },
            'columns' => [
                [
                    'attribute'=>'id',
                    'format'=>'raw',
                    'value'=>function($data){return Html::a($data->id, ['view', 'id'=>$data->id]);}
                ],
                [
                    'attribute' => 'created',
                    'format'=>'raw',
                    'value' => function ($data) {
                        return Html::a(date('d-m-Y H:i:s',$data->created), ['view', 'id'=>$data->id]);
                    },
                ],
                [
                    'attribute' => 'date_last_msg',
                    'format'=>'raw',
                    'value' => function ($data) {
                        return ($data->date_last_msg!=$data->created)?Html::a(date('d-m-Y H:i:s',$data->date_last_msg), ['view', 'id'=>$data->id]):'';
                    },
                    //'visible'=>function($data){return($data->date_last_msg>0)?true:false;}
                ],
                [
                    'attribute' => 'theme',
                    'format'=>'raw',
                    'label'=>'Тема',
                    'value' => function ($data) {
                        return Html::a($data->themeByLength(), ['view', 'id'=>$data->id]);
                    },
                ],
                [
                    'format'=>'raw',
                    'attribute'=>'prioritet',
                    'value'=>function($data){
                        return  Html::a($data->getPrioritetColor(), ['view', 'id'=>$data->id]);
                    }
                ],

                [
                    'format'=>'raw',
                    'attribute'=>'author_id_last_msg',
                    'value'=>function($data){
                        return Html::a(($data->authorLastMsg)?$data->authorLastMsg->email:'', ['view', 'id'=>$data->id]);
                    },
                    'visible'=>function($data){return($data->authorLastMsg)?true:false;},
                ],
                [
                    'format'=>'raw',
                    'label'=>'Статус',
                    'attribute'=>'status',
                    'value'=>function($data){
                        return Html::a($data->statusName, ['view', 'id'=>$data->id]);
                    }
                ],
            ],
        ]); ?>
    </div>

</div>