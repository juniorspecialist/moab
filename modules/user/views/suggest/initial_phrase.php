<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 14:41
 */

echo $form->field($model, 'category_id')
    ->dropDownList(
        \yii\helpers\ArrayHelper::map(
            \app\models\Category::find()
                ->select(['id','title'])
                ->where(['user_id'=>Yii::$app->user->id])
                ->orderBy('id DESC')
                ->all()
            ,'id','title'
        )
    );

//Label для поля «Исходная ключевая фраза»
echo $form->field($model, 'source_phrase')
    ->textarea(['cols'=>5,'rows'=>15,'onKeyUp'=>'countLines(this)'])
    ->label("Добавьте одну или несколько ключевых фраз, по которым будет осуществляться выборка (не более ".Yii::$app->user->identity->suggest_limit_words." фраз):");

?>
<script>
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
</script>
<br>
Строк: <span id="source_phrase_count">0</span>