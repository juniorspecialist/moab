<?php
/**
 * Default view of maintenance mode component for Yii framework 2.x.x version.
 *
 * @version 0.2.0
 * @author BrusSENS (Brusenskiy Dmitry) <brussens@nativeweb.ru>
 * @link https://github.com/brussens/yii2-maintenance-mode
 */
use yii\helpers\Html;
?>
<h3>Работа сайта временно приостановлена!</h3>
<div>
    <p>
        <?php if (Yii::$app->maintenanceMode->message): ?>

            <?php echo Yii::$app->maintenanceMode->message; ?>

        <?php endif; ?>
    </p>
</div>