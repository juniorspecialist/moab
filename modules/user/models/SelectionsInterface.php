<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.15
 * Time: 15:59
 */

namespace app\modules\user\models;


interface SelectionsInterface {

    //для каждой выборки формируем своё описание - свой набор параметров, которые юзер всегда может просмотреть
    function createTotalInfo();
}