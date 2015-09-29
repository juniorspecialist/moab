<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.15
 * Time: 10:19
 */
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user app\modules\user\models\User */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/reset-password', 'token' => $user->password_reset_token]);
?>

    Здравствуйте!<br><br>

    Пройдите по ссылке, чтобы сменить пароль:<br><br>

<?= Html::a(Html::encode($resetLink), $resetLink) ?>