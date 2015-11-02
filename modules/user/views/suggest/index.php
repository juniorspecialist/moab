<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 14:34
 */
use yii\helpers\Html;
use app\components\widgets\UserCategoryWidget;
use yii\bootstrap\Modal;


$this->title = 'Выборки: '.$base->title;
//$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//Yii::$app->getSession()->setFlash('error', 'Успешно добавили выборку(и)');
?>


<div id="custom-error-msg" class="alert-danger alert" style="display: none">
    <button type="button" id="close_danger_alert" class="close"  aria-hidden="true">×</button>
    <span class="error-text-msg-danger-alert"></span>
</div>

<div class="row">
    <div class="suggest_wordstat_control">
        <div class="suggest_wordstat_buttons">
            <div class="col-md-8">
                <div class="fixed-left">
                    <?php
                        echo Html::a('Создать выборку', ['create'] ,['class'=>'btn btn-danger control margin-right']);
                        echo UserCategoryWidget::widget();
                        echo Html::a('Удалить отмеченные выборки', '#' ,['class'=>'btn btn-warning control ', 'id'=>'delete_checked_selects_btn','delete'=>\yii\helpers\Url::to(['delete'])]);
                    ?>
                </div>
                <div class="suggest_wordstat_base_info " >
                    <?php  if(!empty($base->last_update) && !empty($base->next_update) && !empty($base->count_keywords) && !empty($base->add_in_update)){?>
                        <?php echo Html::tag('div',$base->getAttributeLabel('last_update').': '.$base->getAttribute('last_update'),['class'=>'last_update']);?>
                        <?php echo Html::tag('div',$base->getAttributeLabel('next_update').': '.$base->getAttribute('next_update'),['class'=>'next_update']);?>
                        <?php echo Html::tag('div',$base->getAttributeLabel('count_keywords').': '.$base->getAttribute('count_keywords'),['class'=>'next_update']);?>
                        <?php echo Html::tag('div',$base->getAttributeLabel('add_in_update').': '.$base->getAttribute('add_in_update'),['class'=>'next_update']);?>
                    <?php }?>
                </div>
            </div>
            <div class="col-md-2">
                <?php
                echo Html::dropDownList('change_category',
                    null,
                    \yii\helpers\ArrayHelper::map(\app\models\Category::getCategoryArrayByUser(),'id', 'title'),
                    [
                        'prompt'=>'Переместить отмеченные в группу',
                        'class'=>'form-control fixed-width',
                        'id'=>'suggest_change_category_list',
                        'url'=>\yii\helpers\Url::to(['/user/suggest/change-category'])
                    ]
                );
                ?>
            </div>
            <div class="col-md-2">
                <div class="search-suggest-wordstat">
                    <form method="get"  action="" >
                        <div class="input-group fixed-width">
                            <a class="clear ng-hide"  tabindex="0" aria-hidden="true"></a>
                            <?=Html::activeInput('text',$searchModel,'search',['id'=>'search_field', 'class'=>'form-control ','placeholder'=>'Поиск', 'style'=>'width: 100%'])?>
                            <a href="#" class="clear-search" style="position: relative; vertical-align:middle" onclick="$('#search_field').val(''); window.location='<?=\yii\helpers\Url::to(['/user/suggest/index'])?>'; return false;"> <i class="fa fa-times"></i> &nbsp;&nbsp;
                            </a>
                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="submit"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//оборачиваем ДИВ с таблицей, для обновления только этого блока с данными, по необходимости
echo Html::tag('div', $this->render('_grid',['dataProvider' => $dataProvider]),['id'=>'suggest-grid-table']);

//запуск периодического обновления таблицы результатов пользователя
$js = <<< 'SCRIPT'

function jqxhr(){

    //получаем список ID, по которым надо обновить статус в таблице
    var key =  $('#suggest-wordstat-grid td').find(".wait,.execute").closest('tr').find('input[type=checkbox]');

    var index;

    var list = [];

    for (index = key.length - 1; index >= 0; --index) {
       list.push($(key[index]).val());
    }

    if(list.length>0){
        $.ajax({
            url: "",
            //type:"post",
            timeout:10000,
            data:{'SelectionsSuggestSearch':{'ids': list} },
            dataType: "json",
            contentType: "application/json",
            success: function (data, textStatus) {
                var index;
                for (index = data.length - 1; index >= 0; --index) {
                   $('span.status_'+data[index].id).html(data[index].status);
                   $('span.results_count_'+data[index].id).html(data[index].results_count);
                   $('span.preview_'+data[index].id).html(data[index].preview);
                   $('span.download_'+data[index].id).html(data[index].download);
                }
                clearTimeout(jqxhr);
                setTimeout(jqxhr, 5000);
            },
            error:function(data, textStatus, errorThrown){
                clearTimeout(jqxhr);
                setTimeout(jqxhr, 5000);
            }
        });
    }
}

setTimeout(jqxhr, 5000);

SCRIPT;
$this->registerJs($js);


Modal::begin([
    'header' => "<h4>Параметры выборки</h4>",
    'id'=>'suggest_modal_info_win',
    'toggleButton' =>
        [
        //'label' => $button_label,
        'style'=>'display:none',
    ]
]);
Modal::end();
?>


<style>
    a:hover{
        cursor:pointer;
    }
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        /*height: 450px;*/
        overflow-y: auto;
    }
</style>
