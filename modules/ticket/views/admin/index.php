<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Тикеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financy-index">

    <?=Html::a('<img align="center" width="100%" src="/img/Baner3.gif" style="padding:15px 0px;">', 'http://moab.pro', ['target'=>'_blank'])?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function($model, $key, $index, $grid){
                if($model->is_new_for_admin == 1)
                {
                    return ['class' => 'success'];
                }
            },
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
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'prioritet', \app\models\Tickets::getPrioritetList(),['class'=>'form-control','prompt' => '']),
                ],
                [
                    'format'=>'raw',
                    'attribute'=>'author_id',
                    'value'=>function($data){
                        return Html::a($data->author->email, ['view', 'id'=>$data->id]);
                    }
                ],

                [
                    'format'=>'raw',
                    'attribute'=>'author_id_last_msg',
//                    'value'=>function($data){
//                        return Html::a($data->authorLastMsg->email, ['view', 'id'=>$data->id]);
//                    }
                    'value'=>function($data){
                        return Html::a(($data->authorLastMsg)?$data->authorLastMsg->email:'', ['view', 'id'=>$data->id]);
                    },

                ],
                [
                    'format'=>'raw',
                    'label'=>'Статус',
                    'attribute'=>'status',
                    'value'=>function($data){
                        return Html::a($data->statusName, ['view', 'id'=>$data->id]);
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'status', \app\models\Tickets::getStatusList(),['class'=>'form-control','prompt' => '']),
                ],
            ],
        ]); ?>
    </div>

</div>
