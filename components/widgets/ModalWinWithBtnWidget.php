<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.10.15
 * Time: 17:33
 */

namespace app\components\widgets;


/*
виджет для отображения различной информации в диалоговой окне
передаём параметры виджета: - информация модального окна, название кнопки
виджет возвращает - кнопку, при нажатии на которую видим диалоговое окно
*/
use yii\base\Widget;

class ModalWinWithBtnWidget extends Widget{

    private $info;
    private $button_label = 'Кнопка';

    public function init($info, $button_label){
        $this->button_label = $button_label;
        $this->info = $info;
    }


    public function run()
    {
        return $this->render('modal_win_with_btn', [
            'info',$this->info,
            'button_label'=>$this->button_label
        ]);
    }
}