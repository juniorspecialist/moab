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
        'fileuploadadd'=>'function(e, data) {
             if (data.files[0].size>100000) {
                $(".error-text-msg-danger-alert").text("При загрузке файла произошла ошибка. Размер файла не более 1MB ");
                $("#custom-error-msg").show();
                return false;
            }
            /*
            if(data.files[0].type!="application/'.$type.'"){
                $(".error-text-msg-danger-alert").text("При загрузке файла произошла ошибка. Тип файла должен быть '.$type.'");
                $("#custom-error-msg").show();
                return false;
            }*/
        }',
        'fileuploaddone' => 'function(e, data) {
            $("#'.$target_upload_id.'").val(data.result);
        }',
        'fileuploadfail' => 'function(e, data) {
            $(".error-text-msg-danger-alert").text("При загрузке файла произошла ошибка. Тип файла должен быть .'.$type.' и размер не более 1MB ");
            $("#custom-error-msg").show();
        }',
    ],
]);?>