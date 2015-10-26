<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.09.15
 * Time: 16:51
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\components\widgets\Alert;
use yii\bootstrap\Modal;
?>
<?php $form = ActiveForm::begin(['action'=>'/subscription','options' => ['data-pjax' => 1,'id' => 'extension-subscribe-main-form']]); ?>
<?= Alert::widget() ?>
<tr data-key="<?=$index;?>">
    <td>
        <?=$model->title;?>
        <?= $form->field($subs, 'base_id')->hiddenInput(['value'=>$model->id])->label(false); ?>
    </td>
    <td><?= $form->field($subs, 'one_month')
            ->checkbox(['style'=>'display:none','label'=>''])
            ->label($model->one_month_user_info,['style'=>'font-weight:400']); ?>
    </td>
    <td><?= $form->field($subs, 'three_month')
            ->checkbox(['style'=>'display:none','label'=>''])
            ->label($model->three_month_user_info,['style'=>'font-weight:400']); ?>
    </td>
    <td><?= $form->field($subs, 'six_month')
            ->checkbox(['style'=>'display:none','label'=>''])
            ->label($model->six_month_user_info,['style'=>'font-weight:400']); ?>
    </td>
    <td><?= $form->field($subs, 'twelfth_month')
            ->checkbox(['style'=>'display:none','label'=>''])
            ->label($model->twelfth_month_user_info,['style'=>'font-weight:400']); ?>
    </td>

    <td><?= $form->field($subs, 'eternal_period')
            ->checkbox(['style'=>'display:none','label'=>''])
            ->label($model->eternal_period_user_info,['style'=>'font-weight:400']); ?>
    </td>
    <td>
        <?php
        //если подписка вечная, то не показываем кнопки
        if($subs->to<4133890800)
        {
            if($subs->isExpired()){
                echo Html::button(!$subs->isNewRecord ? 'Продлить' : 'Подписаться',
                    ['value'=>\yii\helpers\Url::to(['/subscribe/',
                        'id'=>$model->id]),'class'=>'modalWin btn btn-primary',
                        'service'=>!$subs->isNewRecord ? 'Продлить подписку на '.$model->title : 'Подписаться на '.$model->title,
                        'style'=>'width:167px'
                    ]
                );

            }else{
                echo Html::button(!$subs->isNewRecord ? 'Продлить' : 'Подписаться',
                    ['value'=>\yii\helpers\Url::to(['/subscribe/',
                        'id'=>$model->id]),'class'=>'modalWin btn btn-primary',
                        'service'=>!$subs->isNewRecord ? 'Продлить подписку на '.$model->title : 'Подписаться на '.$model->title,
                        'style'=>'width:167px'
                    ]
                );
            }
        }

        if($moab_base)
        {
            echo Html::a('Ваши выборки' , ['/user/metrika/index'],
                ['class'=>'btn btn-success',
                    'id'=>'modalWindowDetailBtn1',
                    'style'=>'width:167px; margin-top:5px;'
                ]
            );
        }else{
            if($subs->isExpired()){
                //формируем ссылку по кнопке, на основании веб-версия бд или нет
                echo Html::a('Как подключиться' , $model->getUrlInfoBase(),
                    ['class'=>'btn btn-success',
                        'id'=>'modalWindowDetailBtn1',
                        'style'=>'width:167px; margin-top:5px;'
                    ]
                );
            }
        }

        //echo '<pre>'; print_r($subs);die();
        //подписка на базу было не акционной(куплена)+ есть возможность апгрейда до бд-вордстат
        if(!$subs->isNewRecord)
        {
            if($subs->base->id==Yii::$app->params['subsribe_moab_suggest'] && $subs->share==0)
            {
                echo Html::a('Апгрейд до базы Яндекс.Подсказки + Вордстат' , ['/ticket'],
                    [/*'class'=>'btn btn-success',*/
                        //'id'=>'modalWindowDetailBtn1',
                        'style'=>'margin-top:5px;'
                    ]
                );
            }
        }


        ?>
    </td>
    <td><?=$subs->desc?></td>
</tr>