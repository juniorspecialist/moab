<?php
use yii\widgets\Breadcrumbs;
//use dmstr\widgets\Alert;
use app\components\widgets\Alert;

?>
<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>
        <div class="row text-right">
            <?=
            Breadcrumbs::widget(
                [
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    //'options'=>['style'=>'background-color:none'],
                ]
            ) ?>
        </div>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="alert alert-info">
        <strong><i class="fa fa-warning"></i> Не можете разобраться, или что-то пошло не так?</strong>
        <?=\yii\helpers\Html::a('Напишите нам',(Yii::$app->user->isGuest)?'http://moab.pro/#contacts':\yii\helpers\Url::to(['/ticket/ticket/index']))?>, и мы Вам поможем!
    </div>
</footer>

<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>