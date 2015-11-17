<?php

namespace app\modules\user\models;

use app\models\Selections;
use Yii;
use yii\widgets\DetailView;

/**
 * This is the model class for table "selections_suggest".
 *
 * @property integer $id
 * @property integer $selections_id
 * @property integer $potential_traffic
 * @property integer $source_words_count_from
 * @property integer $source_words_count_to
 * @property integer $position_from
 * @property integer $position_to
 * @property integer $suggest_words_count_from
 * @property integer $suggest_words_count_to
 * @property integer $length_from
 * @property integer $length_to
 * @property integer $need_wordstat
 * @property integer $wordstat_syntax
 * @property integer $wordstat_from
 * @property integer $wordstat_to
 * @property integer $base_id
 * @property integer $category
 * @property string $source_phrase
 * @property string $hash
 *
 * @property Selections $selections
 */
class SelectionsSuggest extends \yii\db\ActiveRecord implements SelectionsInterface
{

    public $base_id;
    public $category;
    public $source_phrase;
    public $minus_words;
    public $hash;

    //модель выборок - при создании модели храним связь
    public $selection;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'selections_suggest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['selections_id', 'potential_traffic', 'source_words_count_from', 'source_words_count_to', 'position_from',
                'position_to', 'suggest_words_count_from', 'suggest_words_count_to', 'length_from', 'length_to', 'need_wordstat',
                'wordstat_syntax', 'wordstat_from', 'wordstat_to'], 'required'],

            [['selections_id', 'potential_traffic', 'source_words_count_from', 'source_words_count_to', 'position_from',
                'position_to', 'suggest_words_count_from', 'suggest_words_count_to', 'length_from', 'length_to', 'need_wordstat',
                'wordstat_syntax', 'wordstat_from', 'wordstat_to', 'base_id'], 'integer'],

            //
            [['minus_words'], 'safe'],
        ];
    }

    /*
     * ID базы подписок к которой подвязана данная модель
     */
    public function getBase_id(){
        return Yii::$app->params['subscribe_suggest_and_wordstat'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'selections_id' => 'Выборка ID',
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

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSelections()
    {
        return $this->hasOne(Selections::className(), ['id' => 'selections_id']);
    }

    /*
     * формируем описание основных параметров выборки для юзера
     */
    public function createTotalInfo(){
        return DetailView::widget([
            'model' => $this,
            'attributes' => [
                [
                    'label'=>'Исходная ключевая фраза:',
                    'value'=>$this->selection->source_phrase,
                ],
                [
                    'label'=>'В группе:',
                    'value'=>$this->selection->category->title,
                ],
                [
                    'label'=>'Потенциальный траффик:',
                    'value'=>$this->getPotentialTrafficName(),
                ],
                [
                    'label'=>'Количество слов в исходной фразе:',
                    'value'=>'от '.$this->source_words_count_from.' до '.$this->source_words_count_to,
                    'visible'=>($this->potential_traffic == Selections::POTENCIAL_TRAFFIC_USER)?true:false,
                ],
                [
                    'label'=>'Позиция подсказки:',
                    'value'=>'от '.$this->position_from.' до '.$this->position_to,
                    'visible'=>($this->potential_traffic == Selections::POTENCIAL_TRAFFIC_USER)?true:false,
                ],
                [
                    'label'=>'Количество слов в подсказке:',
                    'value'=>'от '.$this->suggest_words_count_from.' до '.$this->suggest_words_count_to,
                ],
                [
                    'label'=>'Длина подсказки (симв.):',
                    'value'=>'от '.$this->length_from.' до '.$this->length_to,
                ],
                [
                    'label'=>'Минус-слова:',
                    'value'=>$this->getMinusWordsTextJson(),
                    'visible'=>($this->getMinusWordsTextJson())?true:false,
                ],
                [
                    'label'=>'Параметры Wordstat:',
                    'value'=>'синтаксис - '.$this->getWordStatSyntaxName().', частота от '.$this->wordstat_from.' до '.$this->wordstat_to,
                    'visible'=>($this->need_wordstat == 1)?true:false,
                ]
            ],
        ]);
    }

    /*
     * текстовое обозначение потенциального трафика
     */
    private function getPotentialTrafficName()
    {
        $list = Selections::getPotencialTraffic();

        return $list[$this->potential_traffic];
    }
    /*
     * по выбранному типу совпадений-сравнений в ключевике - выводим пример/описание
     */
    private function getWordStatSyntaxName()
    {
        $list = Selections::getWordsStatSyntax();

        return $list[$this->wordstat_syntax];
    }

    /*
     * преобразуем список минус-слов к нужному формау, для информации об выборке
     */
    public function getMinusWordsTextJson(){

        if($this->minus_words){

            return str_replace(' ,', ',', implode(', ', $this->minus_words));
        }

        //нет минус-слов, значит пустое значение выводим
        return '';
    }
}
