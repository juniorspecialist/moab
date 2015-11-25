<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.15
 * Time: 11:25
 */

namespace app\components\widgets;


use app\models\UserSubscription;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

/*
 * виджет пользовательского меню пользователя
 * на основании его подписок(доступов) формируются его пункты меню
 */

class UserMenuWidget extends Widget{

    public function init(){
        parent::init();
    }


    public function run()
    {
        $links = [];

        //определяем роль ползьзователя
        if(!\Yii::$app->user->isGuest)
        {
            //собираем подписки по веб-версиям, по пользователю(АКТУАЛЬНЫЕ)
            if(!\Yii::$app->user->identity->isAdmin()){

                $links[] = ['label' => '<i class="fa fa-file-code-o"></i><span>Подписки</span>', 'url' => ['/subscription'],'active' => $this->checkController('subscription/index')];

                //получаем список активных подписок юзера
                $subscribes = UserSubscription::activeUserSubscribe(\Yii::$app->user->id);

                //по каждой активной подписке получаем параметры для формирования меню
                foreach($subscribes as $subscribe){
                    $links[] = $this->configParamsMenu($subscribe['base_id']);
                }

                $urls = [
                    ['label' => '<i class="fa fa-dollar"></i><span>Финансы</span>', 'url' => Url::to(['/financy']),'active' => $this->checkController('financy/index')],
                    [
                        'label' => '<i class="fa fa-user"></i><span>Профиль</span>', //for basic
                        'url' => ['/profile'],
                        'active' => $this->checkController('default/profile'),
                    ],
                    ['label' => '<i class="fa fa-edit"></i><span>Тикеты</span>', 'url' => ['/ticket/ticket/index'],  'active' => $this->checkController('ticket/index')],
                    ['label' => '<i class="fa fa-briefcase"></i><span>Документы</span>', 'url' => ['/user/doc/index'],  'visible'=>false,'active' => $this->checkController('doc/index')],
                ];

                $links = ArrayHelper::merge($links, $urls);

            }else{
                $links = [
                    ['label' => '<i class="fa fa-file-code-o"></i><span>Базы</span>', 'url' => ['/admin/base/']],
                    ['label' => '<i class="fa fa-user"></i><span>Пользователи</span>', 'url' => ['/admin/default/users/']],
                    ['label' => '<i class="fa fa-plus"></i><span>Финансы</span>', 'url' => ['/admin/financy/']],
                    ['label' => '<i class="fa fa-dashboard"></i><span>Аккаунты</span>', 'url' => ['/admin/account/']],
                    ['label' => '<i class="fa fa-dollar"></i><span>Счета</span>', 'url' => ['/admin/chek/']],
                    ['label' => '<i class="fa fa-dollar"></i><span>Тикеты</span> <span class="badge" >'.\app\models\Tickets::countIsNew().'</span>', 'url' => ['/ticket/admin/index']],
                    ['label' => '<i class="fa fa-briefcase"></i><span>Документы</span>', 'url' => ['/admin/doc/index']],
                    ['label' => '<i class="fa fa-plus"></i><span>Акции(промо-коды)</span>', 'url' => ['/action/index']],
                ];
            }
        }

        //$links[] = ['label'=>, 'url'=>'#'];

        return $this->render('user_menu', [
            'links'=>$links,
        ]);
    }

    /*
     * выделяем активный пункт меню
     */
    public function checkController($route){
        return $route === Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;
    }

    /*
     * HTML - опции для пунктов меню
     * $base_id - ID базы(пункта меню)
     */
    public function configParamsMenu($base_id = ''){

        if($base_id){

            $list = $this->configParamsMenu();

            return $list[$base_id];

        }else{
            return [
                //web-suggest
                \Yii::$app->params['subsribe_moab_suggest']=>[
                    'label'=> '<i class="fa fa-tasks"></i><span class="moab-menu">'.\app\models\Base::getTitleBase(Yii::$app->params['subsribe_moab_suggest']).'</span>',
                    'url'=>Url::to(['/user/suggest-main/index']),
                    'active' => $this->checkController('suggest-main/index'),
                    'options'=>['class'=>'suggest-main']
                ],
                //web-moab-pro
                \Yii::$app->params['subscribe_suggest_and_wordstat']=>[
                    'label'=>'<i class="fa fa-tasks"></i><span class="moab-menu">'.\app\models\Base::getTitleBase(Yii::$app->params['subscribe_suggest_and_wordstat']).'</span>',
                    'url'=>Url::to(['/user/suggest/index']),
                    'active' => $this->checkController('suggest/index'),
                    'options'=>['class'=>'suggest-pro1']
                ],

            ];
        }
    }

}