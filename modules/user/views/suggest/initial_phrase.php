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
    ->textarea(['cols'=>5,'rows'=>15])
    ->label("Добавьте одну или несколько ключевых фраз, по которым будет осуществляться выборка (не более ".Yii::$app->user->identity->suggest_limit_words." фраз):");