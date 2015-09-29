<?php
use yii\bootstrap\Nav;

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
                            ['label' => '<i class="fa fa-dashboard"></i><span>Пользователи</span>', 'url' => ['/admin/default/users/']],
                            ['label' => '<i class="fa fa-dashboard"></i><span>Аккаунты</span>', 'url' => ['/admin/account/']],
                            ['label' => '<i class="glyphicon glyphicon-lock"></i><span>Счета</span>', 'url' => ['/admin/chek/']],
                        ],
                    ]
                );
            }else{
                echo Nav::widget(
                        [
                            'encodeLabels' => false,
                            'options' => ['class' => 'sidebar-menu'],
                            'items' => [
                                ['label' => '<i class="fa fa-file-code-o"></i><span>Подписки</span>', 'url' => ['/subscription']],
                                ['label' => '<i class="fa fa-dashboard"></i><span>Финансы</span>', 'url' => ['/financy']],
                                [
                                    'label' => '<i class="glyphicon glyphicon-lock"></i><span>Профиль</span>', //for basic
                                    'url' => ['/profile'],
                                    'visible' =>!Yii::$app->user->isGuest
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
