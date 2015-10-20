<?php

namespace app\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "docs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $doc_type
 * @property integer $status
 * @property string $file
 */
class Docs extends \yii\db\ActiveRecord
{

    const TYPE_DOC_PASSPORT = 1;//паспорт
    const TYPE_DOC_PHOTO_WITH_PASS = 2;//фото с паспортом в руке
    const TYPE_DOC_DRIVER = 3;//вод. удостоверение/загранпаспорт

    public $passport; //паспорт
    public $passport_in_hands;//фото с паспортом в руке
    public $drive_passport;//вод. удостоверение/загранпаспорт

    const STATUS_ACCEPT = 1;//принять
    const STATUS_PROCESS = 2;//на рассмотрении
    const STATUS_CANCEL = 3;//отклонен

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'docs';
    }

    public static function getListStatus(){
        return [
            self::STATUS_ACCEPT=>'Принят',
            self::STATUS_PROCESS=>'На рассмотрении',
            self::STATUS_CANCEL=>'Отклонен',
        ];
    }

    public function getStatusText(){

        $list = self::getListStatus();

        return $list[$this->status];
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['user_id', 'doc_type', 'status', 'file'], 'required'],

            ['user_id', 'default', 'value'=>Yii::$app->user->id],

            [['user_id', 'doc_type', 'status'], 'integer'],

            ['uploaded','default', 'value'=>time()],

            ['user_id', 'default', 'value'=>Yii::$app->user->id],

            [['passport','passport_in_hands','drive_passport'], 'file', 'skipOnEmpty' => true, 'extensions'=>'jpg, gif, png', 'maxSize' => 1024*200, 'tooBig'=>'Размер файла для "{attribute}" не должен превышать 200КБ'],//200KB

            [['file'], 'string', 'max' => 255],
        ];
    }

    public static function typeList(){
        return [
            self::TYPE_DOC_DRIVER=>'Вод. удостоверение/загранпаспорт',
            self::TYPE_DOC_PASSPORT=>'Паспорт',
            self::TYPE_DOC_PHOTO_WITH_PASS=>'Фото с паспортом в руке',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'doc_type' => 'Тип',
            'status' => 'Статус',
            'file' => 'Имя файла',
            'passport'=>'Скан паспорта',
            'passport_in_hands'=>'Фото с паспортом в руке',
            'drive_passport'=>'Водительские права/загран. паспорт',
            'uploaded'=>'Дата загрузки'
        ];
    }

    public function getType(){

        $list = self::typeList();

        return $list[$this->doc_type];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /*
     * если документ загружен инаходится в статусе «на рассмотрении»или «принят»–поле для загрузки этого документа пропадает
     * Если не загружен или находится в статусе «отклонен»-поле появляется и документ можно загрузить
     * $type_doc - тип документа(паспорт или вод. удостоверение)
     */
    public function showeField($type_doc, $data)
    {
        if($data)
        {
            foreach ($data as $row)
            {
                if($row->doc_type==$type_doc)
                {
                    //проверяем, если документ подтверждён и загружен то не создаём поля для его загрузки,
                    if($row->status==Docs::STATUS_ACCEPT )
                    {
                        return false;
                    }

                    //если документ не подтверждён и загружен - создём поле, для перезагрузки
                    if($row->status==Docs::STATUS_PROCESS)
                    {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /*
     * после загрузки документа - обновим данные в БД
     * $type_doc - тип документа
     * $imageName - имя файла, которые мы запишим в бд
     */
    public function updateImage($imageName, $type_doc)
    {
        //проверим есть ли по данному типу уже запись в БД, если есть обновим
        $doc = Docs::findOne(['user_id'=>Yii::$app->user->id, 'doc_type'=>$type_doc]);

        //существует - обновим
        if($doc)
        {
            @unlink(Yii::getAlias('@docsUsers/') .$doc->file);

            $doc->status = Docs::STATUS_PROCESS;
            $doc->file = $imageName;
            $doc->uploaded = time();
            $doc->update(false);

        }else{
            $doc = new Docs();
            $doc->doc_type = $type_doc;
            $doc->file = $imageName;
            $doc->status = Docs::STATUS_PROCESS;
            $doc->uploaded = time();
            $doc->user_id = Yii::$app->user->id;
            $doc->save(false);
        }
    }

    public function getUniqueNamePhoto($extension){

        for($i=0;$i<1000;$i++){

            $name = md5(time(). rand(1,100));

            if(!file_exists(Yii::getAlias('@docsUsers/') . $name . '.' . $extension)){

                return $name . '.' . $extension;

            }
        }
    }

    /*
     * загрузка файлов, документов пользователя
     */
    public function upload()
    {
        if($this->validate())
        {
            //загрузка паспорта
            if($this->passport)
            {
                $name = $this->getUniqueNamePhoto($this->passport->extension);

                $this->passport->saveAs(Yii::getAlias('@docsUsers/') . $name);

                $this->updateImage($name, Docs::TYPE_DOC_PASSPORT);

                //return true;
            }

            //загрузка вод. удостоверения
            if($this->drive_passport)
            {
                $name = $this->getUniqueNamePhoto($this->drive_passport->extension);

                $this->drive_passport->saveAs(Yii::getAlias('@docsUsers/') . $name);

                $this->updateImage($name, Docs::TYPE_DOC_DRIVER);

            }

            //загрузка фото с паспортом
            if($this->passport_in_hands)
            {
                $name = $this->getUniqueNamePhoto($this->passport_in_hands->extension);

                $this->passport_in_hands->saveAs(Yii::getAlias('@docsUsers/') . $name);

                $this->updateImage($name, Docs::TYPE_DOC_PHOTO_WITH_PASS);

            }

            return true;
        }else{
            return false;
        }
    }
}
