<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 05.08.15
 * Time: 13:58
 */

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user app\models\User */
$ticketLink = Yii::$app->urlManager->createAbsoluteUrl(['ticket/admin/view/', 'id' => $model->id]);
?>
Здравствуйте!<br><br>

Был создан тикет № <?=$model->id?>. <br><br>

Тема: <?=$model->theme;?><br><br>

Просмотреть тикет можно по <?=Html::a('ссылке', $ticketLink)?><br><br>

С уважением, компания MOAB.<br>
Лучшая семантика для лучшего бизнеса!