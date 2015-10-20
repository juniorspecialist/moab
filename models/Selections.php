<?php

namespace app\models;

use Yii;

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

    const STATUS_WAIT = 0;//ожидает
    const STATUS_EXECUTE = 1;//выполняется
    const STATUS_DONE = 2;//готово

    //список вариантов значений потенциального траффика
    const POTENCIAL_TRAFFIC_USER = 1;//пользовательский
    const POTENCIAL_TRAFFIC_LOW = 2;//низкий
    const POTENCIAL_TRAFFIC_MEDIUM = 3;// средний
    const POTENCIAL_TRAFFIC_HIGH = 4;//высокий
    const POTENCIAL_TRAFFIC_ANYONE = 5;//любой



    /*
     * список значений от 1 до 10
     */
    static function getNumberList()
    {
        return [
            1=>1,
            2=>2,
            3=>3,
            4=>4,
            5=>5,
            6=>6,
            7=>7,
            8=>8,
            9=>9,
            10=>10,
        ];
    }

    /*
     * список значений потенциального траффика
     */

    static function getPotencialTraffic()
    {
        return [
            self::POTENCIAL_TRAFFIC_USER => 'Пользовательский',
            self::POTENCIAL_TRAFFIC_LOW => 'Низкий',
            self::POTENCIAL_TRAFFIC_MEDIUM => 'Средний',
            self::POTENCIAL_TRAFFIC_HIGH => 'Высокий',
            self::POTENCIAL_TRAFFIC_ANYONE => 'Любой',
        ];
    }

    /*
     * текстовое обозначение потенциального трафика
     */
    public function getPotentialTrafficName()
    {
        $list = self::getPotencialTraffic();

        return $list[$this->potential_traffic];
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
            [['type', 'name', 'source_phrase', 'results_count',  'potential_traffic', 'source_words_count_from', 'source_words_count_to', 'position_from', 'position_to', 'suggest_words_count_from', 'suggest_words_count_to', 'length_from', 'length_to', 'need_wordstat', 'wordstat_syntax', 'wordstat_from', 'wordstat_to', 'hash', 'result_txt_zip', 'result_csv_zip', 'result_xlsx_zip'], 'required'],
            [['user_id', 'type', 'results_count', 'date_created', 'status', 'potential_traffic', 'source_words_count_from', 'source_words_count_to', 'position_from', 'position_to', 'suggest_words_count_from', 'suggest_words_count_to', 'length_from', 'length_to', 'need_wordstat', 'wordstat_syntax', 'wordstat_from', 'wordstat_to'], 'integer'],
            [['name', 'source_phrase'], 'string', 'max' => 255],
            [['hash'], 'string', 'max' => 50],
            //параметры по-умолчанию
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
            ['date_created', 'default', 'value'=>time()],
            ['status', 'default', 'value'=>self::STATUS_WAIT],


            [['result_txt_zip', 'result_csv_zip', 'result_xlsx_zip'], 'string', 'max' => 128]
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
            'type' => 'Тип выборок',
            'name' => 'Name',
            'source_phrase' => 'Исходная ключевая фраза',
            'results_count' => 'кол-во результатов',
            'date_created' => 'дата создания',
            'status' => 'Статус выборки',
            'potential_traffic' => 'Потенциальный траффик',
            'source_words_count_from' => 'Source Words Count From',
            'source_words_count_to' => 'Source Words Count To',
            'position_from' => 'Position From',
            'position_to' => 'Position To',
            'suggest_words_count_from' => 'Suggest Words Count From',
            'suggest_words_count_to' => 'Suggestwords Count To',
            'length_from' => 'Length From',
            'length_to' => 'Length To',
            'need_wordstat' => '0 –ненужна частота по Wordstat1 –нужна частота по Wordstat',
            'wordstat_syntax' => '0 –слово1 слово21 –“слово1 слово2”2 –“!слово1 !слово2”',
            'wordstat_from' => 'Wordstat From',
            'wordstat_to' => 'Wordstat To',
            'hash' => 'MD5 от конкатенации значений',
            'result_txt_zip' => 'ссылка на файл txt',
            'result_csv_zip' => 'ссылка на файл csv',
            'result_xlsx_zip' => 'ссылка на файл xlsx',
            'category_id'=>'Категория',
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
}
