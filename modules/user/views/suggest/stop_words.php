<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.10.15
 * Time: 21:13
 */
echo $form->field($model, 'stop_words')
    ->textarea(['cols'=>5, 'rows'=>15])
    ->label('Добавьте минус-слова, ключевые фразы с которыми не должны присутствовать в выборке (не более '.Yii::$app->user->identity->suggest_limit_stop_words.' минус-слов):');