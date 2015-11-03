<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.15
 * Time: 9:58
 */
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Html;

//use yii\data\ActiveDataProvider;
$this->title = 'Подписки';

?>

    <?=Html::a('<img align="center" width="100%" src="/img/Baner3.gif" style="padding:15px 0px;">', 'http://moab.pro', ['target'=>'_blank'])?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="10%">Сервис</th>
                    <th width="10%">1 мес.</th>
                    <th width="10%">3 мес.</th>
                    <th width="10%">6 мес.</th>
                    <th width="10%">1 год</th>
                    <th width="10%">Вечная</th>
                    <th width="14%">Оформить подписку</th>
                    <th >Параметры подписки</th>
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
                    'subsriptions'=> $subsriptions,
                    'except_list'=> $except_list,
                    //'model'=>$model
                ],
            ]);
            ?>
        </tbody>
        </table>

        <?php
        /*
         * Если пользователь подписан на Moab Base – у него есть номинальная возможность перейти на Pro путем общения
         * с админом и доплаты разницы стоимости между базами. Но граждане, подписавшиеся на Base, могут об этой возможности и не подозревать.
         * Поэтому при подписке на Base после таблицы баз нужно сделать ссылку «Перейти на Pro»,
         */
        if(\app\modules\user\models\User::isSubscribeMoab(Yii::$app->params['subscribe_moab_base_id']))
        {
            echo Html::a('Перейти на Pro', '#');
        }


        //если у пользователя есть активные подписки НЕ на веб-версии баз, тогда покажем ему доступы и инструкции по РДП
        if(\app\models\UserSubscription::userHaveActualSubscribe()){


        ?>

        <div class="profil-view">

        <h3>Как подключиться к базе</h3>
        <?php
        //есть подписки + нет подвязанных акков
        if(!($user->accessServer!=='Нет')
        && ($subsriptions)) {
            echo '<p class="bg-danger"><strong>К сожалению, серверные аккаунты временно закончились. Мы предоставим вам доступ в самое ближайшее время. Приносим извинения за неудобства.</strong></p>';
        }else {
            ?>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                 Если у вас Windows
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
        <ol>
            <li>Cкачайте <?= Html::a('этот файл', ['/rdp'], ['target' => '_blank']) ?> и запустите его двойным кликом. </li>

             <li>Если система предупредит вас о недоверенном издателе – включите флажок «Больше не выводить запрос о подключениях к этому компьютеру» и нажмите «Подключить».<br><br><?= Html::img('http://joxi.ru/bmoBDPbh8ZaNry.jpg') ?><br><br></li>


            <li>В открывшемся окне введите ваш пароль <input onclick="this.select();" value="<?= ($user->accessServer !== 'Нет') ? $user->accessPass : '' ?>" type="text" style="width:170px;border:0;font-weight:bold; " readonly="true"><br>Отметьте флажок «Запомнить учетные данные» и нажмите «ОК». <br><br><?= Html::img('http://joxi.ru/1A5RqlJf3dBZrE.jpg') ?><br><br></li>

             <li>После подключения разово введите API-ключ <input onclick="this.select();" value="<?= $user->api_key ?>" type="text" style="width:230px;border:0;font-weight:bold;" readonly="true"> в открывшееся окно.<br><br><?= Html::img('http://joxi.ru/Vm6kgJ3iPZKM2Z.jpg') ?></li>
        </ol>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                 Если у вас MAC
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
          Воспользуйтесь инструкцией для <a href="http://moab.pro/connect_mac.pdf" target="_blank">MAC</a>
              </div>
            </div>
          </div>

            <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Данные для доступа к базе
                </a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">

        <?php


            echo \yii\widgets\DetailView::widget([
                'model' => $user,
                'attributes' => [
                    [
                        'label'=>'API-ключ',
                        'value'=>$user->api_key,
                    ],
                    [
                        'label'=>'Компьютер',
                        'value'=>$user->accessServer,
                        'visible'=>($user->accessServer!=='Нет')?true:false
                    ],

                    [
                        'label'=>'Пользователь',
                        'value'=>$user->accessLogin,
                        'visible'=>($user->accessServer!=='Нет')?true:false
                    ],

                    [
                        'label'=>'Пароль',
                        'value'=>$user->accessPass,
                        'visible'=>($user->accessServer!=='Нет')?true:false
                    ],

                ],
            ]);
        }
        ?>
              </div>
            </div>
          </div>
          </div>

            </div>


    </div>

<?php
            //модальное окно - доступы юзера и инструкции по подключению
            \yii\bootstrap\Modal::begin([
                'header' => false,
                'size'=>\yii\bootstrap\Modal::SIZE_LARGE,
                'toggleButton' => [
                    //'label' => !$subs->isNewRecord ? 'Продлить' : 'Подписаться',
                    //'tag'=>'button',
                    //'class'=>'btn btn-primary',
                    'style'=>'display:none',
                ],
                'id'=>'modalWindowDetail',
            ]);

            //есть подписки + нет подвязанных акков
            if(!($user->accessServer!=='Нет') && ($user->subscription)) {
                echo '<p class="bg-danger"><strong>К сожалению, серверные аккаунты временно закончились. Мы предоставим вам доступ в самое ближайшее время. Приносим извинения за неудобства.</strong></p>';
            }else {
                echo Html::tag('p', 'Скачайте ' . Html::a('этот файл', ['/rdp'], ['target' => '_blank']) . ' и запустите его двойным кликом');

                echo Html::tag('p', 'Следуйте ' . Html::a('инструкции', ['/info'], ['target' => '_blank']) . ' по подключению');

                echo Html::tag('p', 'Альтернативные способы подключения: ' . Html::a('Windows', 'http://moab.pro/connect_windows.pdf', ['target' => '_blank']) . ' / ' . Html::a('MAC', 'http://moab.pro/connect_mac.pdf', ['target' => '_blank']));
            }

            \yii\bootstrap\Modal::end();
        }

//форма заказа/продления подписки на базу юзером
\yii\bootstrap\Modal::begin([
    'header' => '<h4 class="modal-title">Подписаться</h4>',
    'toggleButton' => [
        //'label' => !$subs->isNewRecord ? 'Продлить' : 'Подписаться',
        //'tag'=>'button',
        //'class'=>'btn btn-primary',
        'style'=>'display:none',
    ],
    'id'=>'modalWondow',
    'clientEvents'=>[
        'shown.bs.modal'=>'function(){$("input:checkbox",".extension-subscribe-form").removeAttr("checked");}',
    ]

]);
\yii\bootstrap\Modal::end();
