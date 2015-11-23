<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.15
 * Time: 13:20
 */
use yii\bootstrap\Nav;

if(!\Yii::$app->user->identity->isAdmin()){



    echo Nav::widget(
        [
            'encodeLabels' => false,
            'options' => ['class' => 'sidebar-menu'],
            'items' => $links,
        ]
    );
}else{
    //для АДМИНА
    echo  Nav::widget(
        [
            'encodeLabels' => false,
            'options' => ['class' => 'sidebar-menu'],
            'items' =>
            $links
        ]
    );
}