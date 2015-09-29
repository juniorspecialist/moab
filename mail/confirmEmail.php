<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.15
 * Time: 10:18
 */
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user app\models\User */
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/confirm-email', 'token' => $user->email_confirm_token]);
?>
Здравствуйте!<br><br>

Вы  зарегистрировались  в личном  кабинете MOAB.pro.  Для  подтверждения  вашего  электронного адреса и активации аккаунта перейдите по этой   <?=Html::a('ссылке', $confirmLink)?>.
<br><br>
Если вы получили это письмо случайно – просто удалите его.
<br><br>
С уважением, компания MOAB.<br>
Лучшая семантика для лучшего бизнеса!