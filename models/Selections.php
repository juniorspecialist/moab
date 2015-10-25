<?php

namespace app\models;

use Yii;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

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
 * @property integer $potential_traffic
 * @property integer $source_words_count_from
 * @property integer $source_words_count_to
 * @property integer $position_from
 * @property integer $position_to
 * @property integer $suggest_words_count_from
 * @property integer $suggestwords_count_to
 * @property integer $length_from
 * @property integer $length_to
 * @property integer $need_wordstat
 * @property integer $wordstat_syntax
 * @property integer $wordstat_from
 * @property integer $wordstat_to
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
     * по выбранному типу совпадений-сравнений в ключевике - выводим пример/описание
     */
    private function getWordStatSyntaxName()
    {
        $list = self::getWordsStatSyntax();

        return $list[$this->wordstat_syntax];
    }

    /*
     * список значений потенциального траффика
     */

    static function getPotencialTraffic()
    {
        return [
            self::POTENCIAL_TRAFFIC_ANYONE => 'Любой',
            self::POTENCIAL_TRAFFIC_USER => 'Пользовательский',
            self::POTENCIAL_TRAFFIC_LOW => 'Низкий',
            self::POTENCIAL_TRAFFIC_MEDIUM => 'Средний',
            self::POTENCIAL_TRAFFIC_HIGH => 'Высокий',
        ];
    }

    /*
     * текстовое обозначение потенциального трафика
     */
    private function getPotentialTrafficName()
    {
        $list = self::getPotencialTraffic();

        return $list[$this->potential_traffic];
    }

    /*
     * формируем текстовое описание для выбранного потенциального траффика
     * параметр используем при формировании общего описания задания по выборке(юзера)
     *  на основании выбранного траффика - формируем структуру нужной инфы
     *
     * Если значение –4 (пользовательский), то в окно параметровпосле «Потенциальный траффик: Пользовательский»
     * добавятся еще 2 строки –Количествослов в исходной фразе: от source_words_count_fromдо source_words_count_toиПозицияподсказки:отposition_fromдоposition_to
     */
    private function getPotencialTrafficTotalInfo()
    {
        $out = 'Потенциальный траффик: '.$this->getPotentialTrafficName().'<br>';

        if($this->potential_traffic == self::POTENCIAL_TRAFFIC_USER)
        {
            $out.='Количество слов в исходной фразе: от '.$this->source_words_count_from.' до '.$this->source_words_count_to.'<br>';
            $out.='Позиция подсказки: от '.$this->position_from.' до '.$this->position_to.'<br>';
        }

        return $out;
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
            [['category_id','source_phrase',  'potential_traffic', 'source_words_count_from', 'source_words_count_to',
                'position_from', 'position_to', 'suggest_words_count_from', 'suggest_words_count_to',
                'length_from', 'length_to', 'need_wordstat', 'wordstat_syntax', 'wordstat_from', 'wordstat_to', 'hash', 'name'], 'required'],

            [['user_id', 'type', 'results_count', 'date_created', 'status', 'potential_traffic', 'source_words_count_from', 'source_words_count_to', 'position_from', 'position_to',
                'suggest_words_count_from', 'suggest_words_count_to', 'length_from', 'length_to', 'need_wordstat', 'wordstat_syntax', 'wordstat_from', 'wordstat_to'], 'integer'],

            [['name'], 'string', 'max' => 255],


            [['hash'], 'string', 'max' => 50],

            //параметры по-умолчанию
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
            ['date_created', 'default', 'value'=>time()],
            ['status', 'default', 'value'=>self::STATUS_WAIT],
            ['type','default','value'=>self::TYPE_SELECT_TIPS_YA],


            [['result_txt_zip', 'result_csv_zip', 'result_xlsx_zip'], 'string', 'max' => 128]
        ];
    }

    /*
     * общая информация о выборке
     * выводим информацию в диалоговом окне для пользователя
     */
    public function getTotalInfo()
    {
        $out = "Исходная ключевая фраза: $this->source_phrase<br>";
        $out.="В группе: ".$this->category->title.'<br>';
        //описание к потенциальному трафику
        $out.=$this->getPotencialTrafficTotalInfo();
        $out.='Количество слов в подсказке: от '.$this->suggest_words_count_from.' до '.$this->suggest_words_count_to.'<br>';
        $out.='Длина подсказки (симв.): от '.$this->length_from.' до '.$this->length_to.PHP_EOL.'<br>';
        //список минус-слов, через разделитель
        $out.=($this->getMinusWordsTextJson())?('Минус-слова: '.$this->getMinusWordsTextJson().'<br>'):'';

        if($this->need_wordstat == 1)
        {
            $out.='Параметры Wordstat: синтаксис - '.$this->getWordStatSyntaxName().', частота от '.$this->wordstat_from.' до '.$this->wordstat_to.'<br>';
        }

        $out.='Источник: '.$this->getTypeSelect().'<br>';

        return $out;
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
            'potential_traffic' => 'Потенциальный траффик',
            'source_words_count_from' => 'Количество слов в исходной фразе От',
            'source_words_count_to' => 'Количество слов в исходной фразе До',
            'position_from' => 'Позиция подсказки От',
            'position_to' => 'Позиция подсказки До',
            'suggest_words_count_from' => 'Количество слов в подсказке От',
            'suggest_words_count_to' => 'Количество слов в подсказке До',
            'length_from' => 'Длина подсказки от',
            'length_to' => 'Длина подсказки до',
            'need_wordstat' => 'Нужны фразы с частотностью Wordstat',//'0 –ненужна частота по Wordstat1 –нужна частота по Wordstat',
            'wordstat_syntax' => 'Вид частотности',
            'wordstat_from' => 'Частотность Wordstat от',
            'wordstat_to' => 'Частотность Wordstat до',
            'hash' => 'MD5 от конкатенации значений',
            'result_txt_zip' => 'ссылка на файл txt',
            'result_csv_zip' => 'ссылка на файл csv',
            'result_xlsx_zip' => 'ссылка на файл xlsx',
            'category_id'=>'Группа',
            'stop_words'=>'Список минус-слов',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinusWords()
    {
        return $this->hasMany(MinusWords::className(), ['selection_id' => 'id']);
    }

    /*
     *выводим текстовое представление списка минус-слов, для отображению юзеру
     */
    public function getMinusWordsText()
    {
        if($this->minusWords)
        {
            return implode(', ', ArrayHelper::map($this->minusWords, 'minus_word', 'minus_word'));
        }

        //нет минус-слов, значит пустое значение выводим
        return '';
    }

    /*
     * чтобы не делать доп. запросы к таблице минус-слов - храним массив минус-слов к json формате в самой таблице выборок
     */
    public function getMinusWordsTextJson(){
        if($this->minus_words){
            return str_replace(' ,',',',implode(', ', json_decode($this->minus_words, true)));
        }
        //нет минус-слов, значит пустое значение выводим
        return '';
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


    /*
     * формируем данные для предварительного просмотра результатов выборки
     */
    public function previewInfo()
    {

    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            MinusWords::deleteAll(['selection_id'=>$this->id]);

            Preview::deleteAll(['selection_id'=>$this->id]);

            return true;
        } else {
            return false;
        }
    }
}
