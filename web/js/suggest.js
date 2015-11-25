/**
 * Created by alex on 24.11.15.
 */

function countLines()
{
    var area = document.getElementById("suggestform-source_phrase")
    // trim trailing return char if exists
    var text = area.value.replace(/\s+$/g,"")
    var split = text.split("\n")
    //return split.length
    $('#source_phrase_count').text(split.length);
    return true;
}
function countLinesMinus()
{
    var area = document.getElementById("suggestform-stop_words")
    // trim trailing return char if exists
    var text = area.value.replace(/\s+$/g,"")
    var split = text.split("\n")
    //return split.length
    $('#source_phrase_count_minus').text(split.length);
    return true;
}

$(document).ready(function(){

    //клик по полям выбора файлов
    $(document).on('click','#import_txt_btn',function(e){
        e.preventDefault();
        $('#import_txt_model').click();
    });
    $(document).on('click','#import_csv_btn',function(e){
        e.preventDefault();
        $('#import_csv_model').click();
    });

    $(document).on('click','#import_txt_minus_words_btn',function(e){
        e.preventDefault();
        $('#import_txt_model_minus_words').click();
    });
    $(document).on('click','#import_csv_munis_words_btn',function(e){
        e.preventDefault();
        $('#import_csv_model_munis_words').click();
    });
});