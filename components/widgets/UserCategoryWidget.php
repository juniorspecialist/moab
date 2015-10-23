<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.10.15
 * Time: 13:45
 */

namespace app\components\widgets;


use yii\base\Widget;

class UserCategoryWidget extends \yii\base\Widget {

    public function init(){
        parent::init();
    }

    public function run(){

        return $this->render('user_category');

    }
}