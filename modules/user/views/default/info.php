<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28.07.15
 * Time: 15:36
 */

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.04.15
 * Time: 13:50
 */

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = 'Как подключиться';
$this->params['breadcrumbs'][] = ['label' => 'Как подключиться'];

?>
<div class="profil-view">


<?php
//есть подписки + нет подвязанных акков
if(!($user->accessServer!=='Нет') && ($user->subscription)) {
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
                'format'=>'raw',
                'value'=>Html::textInput('api_key',$user->api_key, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
            ],
            [
                'label'=>'Компьютер',
                'format'=>'raw',
                'value'=>Html::textInput('access_server',$user->accessServer, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
                'visible'=>($user->accessServer!=='Нет')?true:false
            ],

            [
                'label'=>'Пользователь',
                'format'=>'raw',
                'value'=>Html::textInput('access_user',$user->accessLogin, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
                'visible'=>($user->accessServer!=='Нет')?true:false
            ],

            [
                'label'=>'Пароль',
                'format'=>'raw',
                'value'=>Html::textInput('access_pass',$user->accessPass, ['style'=>'width:350px;border:0;font-weight:bold;','readonly'=>true,'onclick'=>'this.select()']),
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