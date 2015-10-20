<?php

//установим путь к каталогу для хранения файлов  - документов юзеров
Yii::setAlias('@docsUsers', dirname(__DIR__) . '/documents/');


return [
    'adminEmail' => 'we@moab.pro',
    'supportEmail' => 'we@moab.pro',
    'user.passwordResetTokenExpire' => 3600,
    'admins'=>['we@moab.pro'],//список админов
    'subscribe_moab_pro_id'=>4,//ID подписки на базу МОБА-про
    'subscribe_moab_base_id'=>3,//ID подписки на базу МОБА-бейс
    'subsribe_moab_suggest'=>1,//ID подписки на базу яндекс-подсказки
    'unisender.api_key'=>'5i1upwukyf4hebus7iwnsihryprg3fayyti9y67e'
];
