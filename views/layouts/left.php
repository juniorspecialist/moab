<?php
use yii\bootstrap\Nav;

/*
$checkController = function ($route) {
    echo '<span style="display:none">'.$route.'|'.$this->context->getUniqueId().'</span><br>';
    return $route === $this->context->getUniqueId();
};*/


$checkController = function ($route) {
    return $route === Yii::$app->controller->id.'/'.$this->context->action->id/*$this->context->getUniqueId()*/;
};

if(!Yii::$app->user->isGuest){
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <?php
            if(\Yii::$app->user->identity->isAdmin()){
                echo  Nav::widget(
                    [
                        'encodeLabels' => false,
                        'options' => ['class' => 'sidebar-menu'],
                        'items' => [
                            ['label' => '<i class="fa fa-file-code-o"></i><span>Базы</span>', 'url' => ['/admin/base/']],
                            ['label' => '<i class="fa fa-user"></i><span>Пользователи</span>', 'url' => ['/admin/default/users/']],
                            ['label' => '<i class="fa fa-dashboard"></i><span>Аккаунты</span>', 'url' => ['/admin/account/']],
                            ['label' => '<i class="fa fa-dollar"></i><span>Счета</span>', 'url' => ['/admin/chek/']],
                            ['label' => '<i class="fa fa-dollar"></i><span>Тикеты</span> <span class="badge" >'.\app\models\Tickets::countIsNew().'</span>', 'url' => ['/ticket/admin/index']],
		                    ['label' => '<i class="fa fa-briefcase"></i><span>Документы</span>', 'url' => ['/admin/doc/index']],
                            ['label' => '<i class="fa fa-plus"></i><span>Акции</span>', 'url' => ['/action/index']],
                        ],
                    ]
                );
            }else{
                echo Nav::widget(
                        [
                            'encodeLabels' => false,
                            'options' => ['class' => 'sidebar-menu'],
                            'items' => [
                                ['label' => '<i class="fa fa-file-code-o"></i><span>Подписки</span>', 'url' => ['/subscription'],'active' => $checkController('subscription/index')],
                                ['label' => '<i class="fa fa-dollar"></i><span>Финансы</span>', 'url' => ['/financy'],'active' => $checkController('financy/index')],
                                [
                                    'label' => '<i class="fa fa-user"></i><span>Профиль</span>', //for basic
                                    'url' => ['/profile'],
                                    'visible' =>!Yii::$app->user->isGuest,
                                    'active' => $checkController('default/profile')
                                ],
                                ['label' => '<i class="fa fa-info"></i><span>Как подключиться</span>', 'url'=>['/info'],  'active' => $checkController('default/info'), 'visible'=>\app\models\UserSubscription::userHaveActualSubscribe()],

                                ['label' => '<i class="fa fa-edit"></i><span>Тикеты</span>', 'url' => ['/ticket/ticket/index'],  'active' => $checkController('ticket/index')],

		                        ['label' => '<i class="fa fa-briefcase"></i><span>Документы</span>', 'url' => ['/user/doc/index'],  'active' => $checkController('doc/index')],

                                //['label'=>'Выборки','visible'=>\app\modules\user\models\User::isSubscribeMoab()],
                                //['label'=>'<i class="fa fa-tasks"></i><span class="moab-menu">Moab.Metrika</span>', 'url'=>['/user/metrika/index'], 'visible'=>\app\modules\user\models\User::isSubscribeMoab(Yii::$app->params['subscribe_suggest_and_wordstat']), 'active' => $checkController('metrika/index')],

                                //TODO переделать под вызов ВИДЖЕТА всё меню
                                [
                                    'label'=>'<i class="fa fa-tasks"></i><span class="moab-menu">'.\app\models\Base::getTitleBase(Yii::$app->params['subscribe_suggest_and_wordstat']).'</span>',
                                    'url'=>['/user/suggest/index'],
                                    'visible'=>\app\modules\user\models\User::isSubscribeMoab(Yii::$app->params['subscribe_suggest_and_wordstat']),
                                    'active' => $checkController('suggest/index'),
                                    'options'=>['class'=>'suggest-pro']

                                ],

                            ],
                        ]
                    );
            }

        ?>
    </section>
</aside>
<?php
}
?>
<style>
    .fa-tasks{
        margin-left: 10px;
    }
</style>
