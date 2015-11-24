<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.10.15
 * Time: 21:13
 */
echo $form->field($model, 'stop_words')
    ->textarea(['cols'=>5, 'rows'=>15, 'onKeyUp'=>'countLinesMinus(this)'])
    ->label('Добавьте минус-слова, ключевые фразы с которыми не должны присутствовать в выборке (не более '.Yii::$app->user->identity->suggest_limit_stop_words.' минус-слов):');
?>
<script>
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
</script>
Строк: <span id="source_phrase_count_minus">0</span>