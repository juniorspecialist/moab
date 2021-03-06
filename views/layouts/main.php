<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
//echo '<pre>';
//print_r($this->context->module->id); die();
if( $this->context->module->id=== 'user'){
    if(Yii::$app->controller->id === 'default'){

        if (Yii::$app->controller->action->id === 'login'
            || Yii::$app->controller->action->id === 'request-password-reset'
            ||Yii::$app->controller->action->id === 'signup'
            ||Yii::$app->controller->action->id === 'index'
            || Yii::$app->controller->action->id === 'reset-password') {
            echo $this->render(
                'main-login',
                ['content' => $content]
            );

            Yii::$app->end();
        }

    }
}

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?php
            if(!Yii::$app->user->isGuest) {
                ?>

                <?= $this->render(
                    'header.php',
                    ['directoryAsset' => $directoryAsset]
                ) ?>

                <?= $this->render(
                    'left.php',
                    ['directoryAsset' => $directoryAsset]
                )
                ?>

            <?php
            }

        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>


