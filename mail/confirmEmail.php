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

Здравствуйте!


Для подтверждения адреса пройдите по ссылке:

<?= Html::a(Html::encode($confirmLink), $confirmLink) ?>

Если Вы не регистрировались у на нашем сайте, то просто удалите это письмо.