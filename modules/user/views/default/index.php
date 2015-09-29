<?php
use yii\bootstrap\Tabs;


$this->registerJsFile('/js/post.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<div class="tab-moab" style="">
    <?php
    echo Tabs::widget([
        'options'=>[
            'style'=>'',
        ],
        'items' => [
            [
                'label' => 'Войти',
                'content' => $this->render('login', ['model'=>$login]),
                'active' => (isset(Yii::$app->request->cookies['link']) || Yii::$app->request->get('promo')) ? false: true,
                'options' => ['id' => 'loginID','style'=>''],
            ],
            [
                'label' => 'Регистрация',
                'content' => $this->render('signup', ['model'=>$signup]),
                'options' => ['id' => 'signupID','style'=>''],
                //'headerOptions' => [...],
                //'options' => ['id' => 'myveryownID'],
                'active' => (isset(Yii::$app->request->cookies['link']) || Yii::$app->request->get('promo')) ? true: false,
            ],
        ],
    ]);

    ?>
    <span style="color:#fff;">
        <strong><i class="fa fa-warning"></i> Не можете разобраться, или что-то пошло не так?</strong> <a href="http://moab.pro/#contact">Напишите нам</a>, и мы Вам поможем!
    </span>

</div>
