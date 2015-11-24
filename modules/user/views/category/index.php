<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.15
 * Time: 10:13
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

//форма добавления категории-группы
echo $this->render('form',['model'=>$model]).'<br>';
?>

<?php echo Html::jsFile('/js/bootstrap-editable.min.js');?>

<div id="categorys">
    <?php
        //выводим список групп-категорий по пользователю
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'showHeader'=>false,
            'summary'=>false,
            'tableOptions'=>['id'=>'group_list','class'=>'table table-striped table-bordered'],
            'columns' => [
                [
                    'class' => 'yii\grid\DataColumn',
                    'label'=>'Группа',
                    'format'=>'raw',
                    'value' => function ($data) {
                        if($data->title=='Без группы'){
                            return $data->title;
                        }else{
                            return '<a href="#"  class="mypopover"  style="width:350px" id="group'.$data->id.'" data-type="text"  data-pk="'.$data->id.'" data-title="Укажите группу">'.$data->title.'</a>';
                        }
                    },
                ],
            ],
        ]);
    ?>
</div>

<script>
    $(document).ready(function () {
        $.fn.editable.defaults.mode = 'inline';
        $('a.mypopover').editable({
            type: 'text',
            pk: 1,
            url: '/user/category/index',
            title: 'Укажите название группы',
            validate: function(value) {

                var error = false;

                $("a.mypopover" ).each(function( index ) {
                    if($.trim(value) == $.trim($( this ).text())) {
                        error = true;
                    }
                });

                //if(error==true){return 'Имя группы уже указано в списке';}

                if($.trim(value) == '') {
                    return 'Укажите название группы';
                }

                //установим флаг, перезагрузки страницы
                $('#can_we_refrash_page').val(1);
            },
            success: function(response, newValue) {
                //console.log('username'+newValue);
                //userModel.set('username', newValue); //update backbone model
                $('.modal-body').html(response);
            }
        });


    })
</script>
<style>
    #group_list{
        width: 100%;
    }
    .modal-body{
        /*height: 450px;*/
        overflow-y: auto;
    }
    .modal.in .modal-dialog{
        width: 620px;
        /*height: 250px;*/
    }
    div.editable-input>input{
        width: 450px;
    }
    .form-inline .form-control{
        width: 450px;
    }
    .editable-clear{
        margin-left: 7px;
    }
</style>

