/**
 * Created by alex on 14.04.15.
 */
yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    return message === undefined || yii.confirm(message, $e);
};
yii.confirm = function (message, ok, cancel) {
    bootbox.confirm(message, function (confirmed) {
        if (confirmed) {
            !ok || ok();
        } else {
            !cancel || cancel();
        }
    });
    return false;
}


$(document).ready(function () {

    $(document).on('submit','#form-category', function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function (data) {
                /*if (data && data.result == 1) {
                 $.pjax.reload({container:'#solutionItems'});
                 }*/
                //$.pjax.reload({container:"#categorys"});
                $('.modal-body').html(data);
            }
        });

        return false;
    })

    $(document).on('submit', '.extension-subscribe-form', function (e) {
        e.preventDefault();
        var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),
                success: function (result) {
                    $('.modal-body').html(result);
                }
            });
    });


    $(document).on('click','.modal_photo', function(e){

        $('#modalWinPhoto').modal('show').find('.modal-body').load($(this).attr('href'));

        $('.photo_id').val($(this).attr('alt_id'));
        return false;
    })

    $(document).on('click','.modalWin', function(e){
        $('#modalWondow').modal('show').find('.modal-body').load($(this).attr('value'));
        $('.modal-header h4').html($(this).attr('service'));
    })

    //модальное окно списка групп-категорий
    $(document).on('click','#category_modal_btn',function(e){
        $('#modal_control_category').modal('show').find('.modal-body').css('height','450px').load($(this).attr('value'));
        //$('.mypopover').popover();
        return false;
    })

    $(document).on('click','#modalWindowDetailBtn', function(e){
        $('#modalWindowDetail').modal('show');
    })
    // valid, send form-zakaz
    $("#form-moab-pro").validationEngine('attach', {
        promptPosition : "centerRight",
        scroll: false,
        onValidationComplete: function(form, status){
            if (status == true){
                $.ajax({
                    type: "POST",
                    processData: true,
                    //dataType: 'jsonp',
                    url: '/user/default/moab',
                    data: $('#form-moab-pro').serialize()
                    //crossDomain:true,
                    /*xhrFields: {
                        withCredentials: true
                    }*/
                }).done(function(msg){
                    $('#zakaz2UsAlert').show();
                    $("#form-moab-pro :input").attr("disabled", true);
                    //$('#zakazFormModal').modal('hide');
                    //alert('Спасибо! Ваше сообщение отправлено.');

                }).always(function(){

                });
                return false;
            }
        }
    });

    //пользователь удаляе выбранную категорию(выборок)
    $(document).on('click', '.btn-danger',function(e){

        e.preventDefault();

        var id = $(this).closest('td','a').find('a.mypopover:first').attr('data-pk');

        $.ajax({
            url: '/user/category/delete',
            type: "POST",
            data: 'id='+id,
            success: function (result) {
                //$(this).closest('tr').remove();
                $('.modal-body').html(result);
            }
        });
    })



    //удаление выбранных значений из таблицы - подсказки-вордстат
    $(document).on('click','#delete_checked_selects_btn',function(e){

        e.preventDefault();

        //получаем массив выбранных значений
        var keys = $('#suggest-wordstat-grid').yiiGridView('getSelectedRows');

        if (typeof keys !== 'undefined' && keys.length > 0) {
            var url = $(this).attr('delete');//url to send

            $.ajax({
                url: url,
                type: "POST",
                data: { 'ids': keys },
                success: function (result) {
                    if(result==true)
                    {
                        location.reload();
                    }
                }
            });
        }else{

            //уведомление, если юзер не выбрал ни одной выборки
            bootbox.alert("Необходимо выбрать хотя бы одну выборку", function() {
                return true;
            });
        }


        return false;
    });
    //====================форма добавления задания на выборку================

    $(document).on('click','input[name="SuggestForm[need_wordstat]"]',function(){
        if($(this).val()==0)
        {
            $('.wordstat_selects_params').hide();
            $('select.wordstat').attr('disabled',false);
        }else{
            $('.wordstat_selects_params').show();
            $('select.wordstat').attr('disabled','disabled');
        }
    });


    //пользвоатель выбирает различные - потенциальные трафики
    $(document).on('change','#suggestform-potential_traffic', function(){

        //определяем потенциальный трафик
        var potencial_traffic = $('#suggestform-potential_traffic option:selected').val();

        //если не пользовательский выбран, то блокируем все поля на форме
        if(potencial_traffic != 1)
        {

            $('.extra_options').parent('div').addClass('disabled');

            //потенциальный траффик - любой(5)
            if(potencial_traffic == 5)
            {
                //кол-во слов в исходной фразе (1-32)
                $('#suggestform-source_words_count_from').val(1);
                $('#suggestform-source_words_count_to').val(32);

                //позиция подсказки (1-10)
                $('#suggestform-position_from').val(1);
                $('#suggestform-position_to').val(10);
            }

            //потенциальный траффик - высокий(4)
            if(potencial_traffic == 4)
            {
                //кол-во слов в исходной фразе (1-32)
                $('#suggestform-source_words_count_from').val(1);
                $('#suggestform-source_words_count_to').val(1);

                //позиция подсказки (1-10)
                $('#suggestform-position_from').val(1);
                $('#suggestform-position_to').val(8);
            }

            //потенциальный траффик - средний(3)
            if(potencial_traffic == 3)
            {
                //кол-во слов в исходной фразе (1-32)
                $('#suggestform-source_words_count_from').val(5);
                $('#suggestform-source_words_count_to').val(10);

                //позиция подсказки (1-10)
                $('#suggestform-position_from').val(2);
                $('#suggestform-position_to').val(2);
            }

            //потенциальный траффик - низкий(2)
            if(potencial_traffic == 2)
            {
                //кол-во слов в исходной фразе (1-32)
                $('#suggestform-source_words_count_from').val(5);
                $('#suggestform-source_words_count_to').val(10);

                //позиция подсказки (1-10)
                $('#suggestform-position_from').val(3);
                $('#suggestform-position_to').val(3);
            }

        }else{
            $('.extra_options').prop('disabled', false);
            $('.extra_options').parent('div').removeClass('disabled');
        }

    });
})
