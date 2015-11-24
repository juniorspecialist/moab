<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.11.15
 * Time: 14:28
 */

namespace app\modules\user\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadFele extends Model{

    /**
     * @var UploadedFile
     */
    public $uploadFile;
    public $extensions = 'txt';
    public $content;

    public function rules()
    {
        return [
            [['uploadFile'], 'file', 'checkExtensionByMimeType' => false, 'skipOnEmpty' => false, 'extensions' => $this->extensions, 'maxSize' => 1024*1024],
        ];
    }

    public function upload()
    {
        $dir = \Yii::getAlias('@app/runtime/logs/').time().'.'.$this->uploadFile->extension;

        if ($this->validate()) {

            $this->uploadFile->saveAs($dir);

            if(file_exists($dir)){

                //по разширению файла определяем как его читать содержимое
                if($this->uploadFile->extension == 'txt'){
                    $this->content = file_get_contents($dir);
                }
                //если файл CSV
                if($this->uploadFile->extension == 'csv'){
                    $file = fopen($dir, 'r');
                    while (($line = fgetcsv($file)) !== FALSE) {

                        $line = implode(';',$line);

                        $line = iconv('windows-1251', 'utf-8//IGNORE', $line);

                        $line = explode(';', $line);

                        $this->content.=$line[0].PHP_EOL;
                    }
                    fclose($file);
                }
                unlink($dir);
            }

            return true;
        } else {
            return false;
        }
    }

}