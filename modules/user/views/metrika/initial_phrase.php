<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 14:41
 */

echo $form->field($model, 'category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Category::find()->where(['user_id'=>Yii::$app->user->id]),'id','title'));

echo $form->field($model, 'source_phrase')->textarea(['cols'=>5,'rows'=>15]);