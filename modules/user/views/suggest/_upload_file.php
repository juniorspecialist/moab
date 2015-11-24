<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.11.15
 * Time: 16:55
 */
use dosamigos\fileupload\FileUpload;
use app\modules\user\models\UploadFele;
?>
<?= FileUpload::widget([
    'model' => new UploadFele(),
    'attribute' => 'uploadFile',
    'url' => ['upload/upload', 'type' => $type],
    'options'=>[
        'class'=>'btn btn-info',
        'style'=>'display:none',
        'id'=>$id
    ],
    'clientOptions' => [
        'maxFileSize' => 100000,
    ],
    // ...
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
            console.log("done");
            $("#suggestform-source_phrase").val(data.result);
        }',
        'fileuploadfail' => 'function(e, data) {
            $(".error-text-msg-danger-alert").text("При загрузке файла произошла ошибка. Тип файла должен быть .'.$type.' и размер не более 1MB ");
            $("#custom-error-msg").show();
        }',
    ],
]);?>