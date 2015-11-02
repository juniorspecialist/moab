<?php

namespace app\models;

use app\modules\user\models\SelectionsSuggest;
use Yii;
use yii\helpers\Html;


/**
 * This is the model class for table "selections".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $name
 * @property string $source_phrase
 * @property integer $results_count
 * @property integer $date_created
 * @property integer $status
 * @property string $hash
 * @property string $result_txt_zip
 * @property string $result_csv_zip
 * @property string $result_xlsx_zip
 *
 * @property MinusWords[] $minusWords
 * @property Preview[] $previews
 */
class Selections extends \yii\db\ActiveRecord
{

    //статусы для очереди заданий на выборку
    const STATUS_WAIT = 0;//ожидает
    const STATUS_EXECUTE = 1;//выполняется
    const STATUS_DONE = 2;//готово


    const YES = 1;//да
    const NO = 0;//нет

    //типы выборок
    const TYPE_SELECT_TIPS_YA = 2;//подсказок –всегда тип 2
    const TYPE_SELECT_METRIKA = 1;//метрика

    //список вариантов значений потенциального траффика
    const POTENCIAL_TRAFFIC_USER = 1;//пользовательский
    const POTENCIAL_TRAFFIC_LOW = 2;//низкий
    const POTENCIAL_TRAFFIC_MEDIUM = 3;// средний
    const POTENCIAL_TRAFFIC_HIGH = 4;//высокий
    const POTENCIAL_TRAFFIC_ANYONE = 5;//любой


    public $stop_words;//минус слова
    protected $stop_words_exploded; //список минус-слов после преобразования(текста) их в массив
    private $stop_words_limit = 10;//максимальное кол-во слов в листе минус-слов


    const WORD_STAT_SYNTAX_ZERO = 0;//слово1 слово2
    const WORD_STAT_SYNTAX_ONE = 1;//“слово1 слово2”
    const WORD_STAT_SYNTAX_TWO = 2;//


    /*
     * список типов источников для задания(выборки)
     */
    static function getTypeSelectList()
    {
        return [
            self::TYPE_SELECT_METRIKA=>'Метрика',
            self::TYPE_SELECT_TIPS_YA=>'Яндекс.Подсказки',
        ];
    }

    public function getTypeSelect()
    {
        $list = self::getTypeSelectList();
        return $list[$this->type];
    }


    /*
     * список вариантов формата совпадения по сравнению ключей в запросе
     */
    static function getWordsStatSyntax()
    {
        return [
            self::WORD_STAT_SYNTAX_TWO=>'“!слово1 !слово2”',
            self::WORD_STAT_SYNTAX_ZERO=>'слово1 слово2',
            //self::WORD_STAT_SYNTAX_ONE=>'“слово1 слово2”',
        ];
    }

    /*
     * список значений потенциального траффика
     */

    static function getPotencialTraffic()
    {
        return [
            self::POTENCIAL_TRAFFIC_ANYONE => 'Любой',
            self::POTENCIAL_TRAFFIC_HIGH => 'Высокий',
            self::POTENCIAL_TRAFFIC_MEDIUM => 'Средний',
            self::POTENCIAL_TRAFFIC_LOW => 'Низкий',
            self::POTENCIAL_TRAFFIC_USER => 'Пользовательский',
        ];
    }

    //список статусов
    static function getStatusList()
    {
        return [
            self::STATUS_WAIT=>'Ожидает',
            self::STATUS_EXECUTE=>'Выполняется',
            self::STATUS_DONE=>'Готово',
        ];
    }

    //получаем англ. название статуса
    static function getEngNameStatus($status){
        if($status == self::STATUS_DONE){
            return 'done';
        }
        if($status == self::STATUS_EXECUTE){
            return 'execute';
        }
        if($status == self::STATUS_WAIT){
            return 'wait';
        }

    }

    /*
     * текстовое определение статуса выборки
     */
    public function getStatusName()
    {
        $list = self::getStatusList();
        return $list[$this->status];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'selections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id','source_phrase',  'hash', 'name'], 'required'],

            [['user_id', 'type', 'results_count', 'date_created', 'status'], 'integer'],

            [['name'], 'string', 'max' => 255],

            [['hash'], 'string', 'max' => 50],

            //параметры по-умолчанию
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
            ['date_created', 'default', 'value'=>time()],
            ['status', 'default', 'value'=>self::STATUS_WAIT],
            ['type','default','value'=>self::TYPE_SELECT_TIPS_YA],


            [['result_txt_zip', 'result_csv_zip', 'result_xlsx_zip'], 'string', 'max' => 256]
        ];
    }

    /*
     * общая информация о выборке
     * выводим информацию в диалоговом окне для пользователя
     */
    public function getTotalInfo()
    {
        return $this->additional_info;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Тип выборок',
            'name' => 'Name',
            'source_phrase' => 'Исходная ключевая фраза',
            'results_count' => 'кол-во результатов',
            'date_created' => 'дата создания',
            'status' => 'Статус выборки',
            'hash' => 'MD5 от конкатенации значений',
            'result_txt_zip' => 'ссылка на файл txt',
            'result_csv_zip' => 'ссылка на файл csv',
            'result_xlsx_zip' => 'ссылка на файл xlsx',
            'category_id'=>'Группа',
            'stop_words'=>'Список минус-слов',
            'additional_info'=>'Параметры выборки(общая информация)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinusWords()
    {
        return $this->hasMany(MinusWords::className(), ['selection_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreviews()
    {
        return $this->hasMany(Preview::className(), ['selection_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(Base::className(), ['id' => 'base_id']);
    }


    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            //доп. данные выборки по подсказкам
            SelectionsSuggest::deleteAll(['selections_id'=>$this->id]);

            //таблица минус-слов, подвязанных к выборке
            MinusWords::deleteAll(['selection_id'=>$this->id]);

            //предварительный просмотр выборок
            Preview::deleteAll(['selection_id'=>$this->id]);

            return true;
        } else {
            return false;
        }
    }

    /*
     * формируем статус для таблички вывода данных
     */
    public function getStatusGrid(){
        //ожидает
        if($this->status == \app\models\Selections::STATUS_WAIT){
            $class = ' <i class="'.\app\models\Selections::getEngNameStatus($this->status).' fa fa-clock-o"></i>';
        }
        //выполняется
        if($this->status == \app\models\Selections::STATUS_EXECUTE){
            $class = '<i class="'.\app\models\Selections::getEngNameStatus($this->status).' fa fa-refresh fa-spin"></i>';
        }//выполнено
        if($this->status == \app\models\Selections::STATUS_DONE){
            $class = '<i class="'.\app\models\Selections::getEngNameStatus($this->status).' fa fa-check"></i>';
        }
        return $class.'&nbsp;'.$this->getStatusName();
    }

    /*
     * формируем столбкц "СКАЧАТЬ"
     */
    public function getLinkGrid(){
        if($this->status==\app\models\Selections::STATUS_DONE && $this->results_count!=0){
            //.' | '.Html::a('XLSX', $data->result_xlsx_zip,['target'=>'_blank'])
            return 'Скачать '. Html::a('TXT',$this->result_txt_zip,['target'=>'_blank']).' | '.Html::a('CSV',$this->result_csv_zip,['target'=>'_blank']);
        }
        return '';

    }

    /*
     * столбец "Просмотр" для таблички выброк
     */
    public function getPreviewGrid(){
        //Высвечивается только для выборок в статусе «Выполнена»
        if($this->status==\app\models\Selections::STATUS_DONE  && $this->results_count!=0)
        {
            return Html::a('Просмотреть',\yii\helpers\Url::to(['/user/suggest/preview','id'=>$this->id]),[
                'target'=>'_blank',
                'class'=>'modal_preview_suggest'
            ]);
        }
        return '';
    }

    /*
     * кол-во результатов для таблички - просмотра выборок
     */
    public function getResultCountGrid(){
        //Высвечивается только для выборок в статусе «Выполнена». Для остальных статусов –пустая строка.
        if($this->status==\app\models\Selections::STATUS_DONE)
        {
            return  \Yii::$app->formatter->asInteger($this->results_count);
        }
        return '';
    }

    /*
     * формируем ссылку на Просмотр Параметров созданной выборки
     */
    public function getParamsInfo(){
        //Ссылка/кнопка на всплывающее окно с параметрами выборки. В этом всплывающем окне будет выводиться информация о выборке,
        return Html::a('Параметры',
            ['#'],
            [
                'modal_info'=>$this->additional_info,
                'class'=>'suggest_params_modal_link'
            ]
        );
    }
}
