/**
 * Created by root on 21.07.15.
 */

$(document).ready(function () {
    $(document).on('submit', '#form-signup', function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            success: function (result) {
                if(result=='ok'){
                    location.reload();
                }else{
                    $('#signup-row').html(result);
                }
            }
        });
    });

    $(document).on('submit', '#login-form', function (e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            success: function (result) {
                if(result=='ok'){
                    location.reload();
                }else {
                    $('#div-login').html(result);
                }
            }
        });
    });
})