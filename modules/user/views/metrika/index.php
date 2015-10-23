<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13.08.15
 * Time: 16:53
 */

use app\components\widgets\UserCategoryWidget;

$this->title = 'Выборки: Яндекс-Метрика';

$this->params['breadcrumbs'][] = $this->title;



echo UserCategoryWidget::widget();
?>
