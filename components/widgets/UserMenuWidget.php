<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.15
 * Time: 11:25
 */

namespace app\components\widgets;


use yii\bootstrap\Widget;

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
        return $this->render('user_menu', [
            'info'=>$this->info,
            'button_label'=>$this->button_label,
            'header'=>$this->header,
            'id'=>$this->id,
        ]);
    }

}