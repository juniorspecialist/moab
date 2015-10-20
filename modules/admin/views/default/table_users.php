<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.15
 * Time: 11:38
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary'=>false,
        'columns' => [
            [
                'label'=>'МОАБ',
                'class' => 'yii\grid\DataColumn',
                'format'=>'raw',
                'visible'=>false,
                'value'=>function($data){
                    return Html::a('MOAB Base',\yii\helpers\Url::to(['/admin/default/subscribe-user']),[

                        'data'=>[
                            'method' => 'post',
                            'confirm'=>'Вы уверены, что хотите дать юзеру('.$data['email'].') подписку на MOAB BASE ?',
                            'params' => [
                                'action' => \yii\helpers\Url::to(['/admin/default/subscribe-user']),
                                'user_id'=>$data['id'],
                                'base_id'=>Yii::$app->params['subscribe_moab_base_id'],
                            ],
                        ],
                        'data-method' => 'post',
                        'class' => 'btn btn-info subscribe_moab btn-xs']
                    ).'<br><br>'.
                    Html::a('MOAB Pro',\yii\helpers\Url::to(['/admin/default/subscribe-user']),[
                            'data'=>[
                                'method' => 'post',
                                'confirm'=>'Вы уверены, что хотите дать юзеру('.$data['email'].') подписку на MOAB PRO ?',
                                'params' => [
                                    'action' => \yii\helpers\Url::to(['/admin/default/subscribe-user']),
                                    'user_id'=>$data['id'],
                                    'base_id'=>Yii::$app->params['subscribe_moab_pro_id'],
                                ],
                            ],
                            'data-method' => 'post',
                            'class' => 'btn btn-info subscribe_moab btn-xs']
                    );
                }

            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Пользователь',
                'format'=>'raw',
                'attribute'=>'email',
                'value' => function ($data) {
                    return Html::a($data['email'],['/admin/default/info-user','id'=>$data['id']], ['target'=>'_blank']);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label'=>'Баланс',
                'format'=>'raw',
                'value' => function ($data) {
                    return $data['balance'];
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Подписки',
                'format'=>'raw',
                'value' => function ($data) {
                    //есть данные по подпискам, по юзеру - получим список его подписок
                    $info = '';
                    if(!empty($data['_subscribe']))
                    {
                        foreach($data['_subscribe'] as $usersubscription){
                            //echo '<pre>'; print_r($usersubscription);
                            foreach($usersubscription as $subcribe_info)
                            {
                                $info.=Html::a($subcribe_info['base']['title'].' до '.date('d-m-Y',$subcribe_info['to']), ['update-subscribe', 'id'=>$subcribe_info['id']]);
                                    //если подписка акционная или не актуальная - НЕ выводим ссылку
                                    if($subcribe_info['share']==0 && $subcribe_info['to']>time()) {
                                        $info.=Html::a('| Вернуть баланс(' . $subcribe_info['base']['title'] . ')',
                                            ['/admin/default/return-sum-subscribe','id'=>$subcribe_info['id']],
                                            [
                                                'style' => 'margin-left:5px',
                                                'data-method' => 'post',
                                                'data-confirm' => 'Вы уверены, что хотите вернуть баланс по подписке - ' . $subcribe_info['base']['title']
                                            ]
                                        ) ;
                                    };

                                $info.='<br>';
                            }
                        }
                    }
                    return $info;
                },
            ],
            [
                'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                'label'=>'Инфо',
                'format'=>'raw',
                'value' => function ($data) {

                    //если указана дата последнего визита пользователя
                    ($data['last_vizit_time'])?$last_time_vizit = date('Y-m-d H:i:s',$data['last_vizit_time']):$last_time_vizit = '';

                    return "Последний вход:<br>".$last_time_vizit.'<br>'.
                        Html::a('История IP', Yii::$app->urlManager->createAbsoluteUrl(['admin/default/history-ip', 'id'=>$data['id']])).' | '.
                        Html::a('Финансы', Yii::$app->urlManager->createAbsoluteUrl(['/admin/default/financy', 'id'=>$data['id']]));
                },
            ],

            [
                'label'=>'Действия',
                'format'=>'raw',
                'attribute'=>'id',
                'value'=>function($data){
                    $out = Html::a('Пополнить баланс',['/admin/default/add-balance','id'=>$data['id']]).'<br>';
                    $out.=Html::a('Заблок./Разблок.', ['/admin/default/change-status', 'id'=>$data['id']]).'<br>';
                    $out.=Html::a('Сменить пароль', ['/admin/default/change-pass', 'id'=>$data['id']]);
                    return $out;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'urlCreator'=>function($action, $model, $key, $index){
                    return [$action,'id'=>$model['id']];
                },
            ],
            //

        ],
    ]); ?>

</div>
