<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.15
 * Time: 14:18
 */

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user app\models\User */
if(!Yii::$app->user->identity->isAdmin())
{
    $ticketLink = Yii::$app->urlManager->createAbsoluteUrl(['ticket/admin/view/', 'id' => $model->id]);
}else{
    $ticketLink = Yii::$app->urlManager->createAbsoluteUrl(['ticket/ticket/view/', 'id' => $model->id]);
}

?>
Здравствуйте!<br><br>

На тикет № <?=$model->id?> был ответ. <br><br>

Тема: <?=$model->theme;?> <br><br>

Просмотреть тикет можно по <?=Html::a('ссылке', $ticketLink)?>

С уважением, компания MOAB.<br>
Лучшая семантика для лучшего бизнеса!