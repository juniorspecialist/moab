<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.15
 * Time: 9:58
 */
use yii\widgets\ListView;
use yii\widgets\Pjax;

//use yii\data\ActiveDataProvider;
$this->title = 'Подписки';

?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>База</th>
                <th>1 мес.</th>
                <th>3 мес.</th>
                <th>6 мес.</th>
                <th>1 год</th>
                <th>Оформить подписку</th>
                <th>Параметры подписки</th>
                <th>Продлить</th>
            </tr>
        </thead>
        <tbody>

        <?php
        echo ListView::widget([
            'id'=>'user_subscription',
            'dataProvider' => $dataProvider,
            'itemView' => '_subscribe',

//            'itemView' => function ($model, $key, $index, $widget) {
//                return $this->render('_list', ['model' => $model]);
//            },
            'viewParams' => [
                'fullView' => true,
                'context' => 'main-page',
                'subsriptions'=>$subsriptions,
                //'model'=>$model
            ],
        ]);
        ?>
    </tbody>
    </table>

