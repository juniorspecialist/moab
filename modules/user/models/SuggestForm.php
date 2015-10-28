<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.10.15
 * Time: 12:02
 */

namespace app\modules\user\models;


use app\models\Selections;
use Yii;
use yii\base\Model;

/*
 * модель для валидации формы добавления задания на выборку-для яндекс-подсказок
 */

class SuggestForm extends Model
{

    public $category_id;
    public $source_phrase;
    public $potential_traffic;
    public $source_words_count_from = 1;
    public $source_words_count_to = 32;
    public $position_from = 1;
    public $position_to = 10;
    public $suggest_words_count_from = 1;
    public $suggest_words_count_to = 32;
    public $length_from = 1;
    public $length_to = 256;
    public $need_wordstat;
    public $wordstat_syntax = Selections::WORD_STAT_SYNTAX_TWO;
    public $wordstat_from;
    public $wordstat_to;
    public $stop_words;
    //public $stop_words_limit = 100;//сколько фраз в рамках списка минус-слов, разрешено добавлять юзеру
    protected $stop_words_exploded; //список минус-слов после преобразования(текста) их в массив
    private $source_phrase_list;
    public $hash;
    public $type = Selections::TYPE_SELECT_TIPS_YA;
    public $base_id;//

    //public $source_phrase_list_limit = 10;//сколько фраз в рамках одного поля выборки, юзер может добавить фраз для выборок

    private $hash_list = [];//список хеш-сумм, которые формируем по каждой выборке(каждому ключевому слову)


    /*
     * валидируем исходную ключевую фразу
     * преобразоываем текст в массив ключевых фраз, которые отдельно валидируем каждую
     * после успешной валидации формируем массив ключевых слов, на основании которых будем создавать задания на выборки
     */
    public function validateSourcePhrase()
    {
        if(!$this->hasErrors())
        {

            //каждый ключевик с новой строки
            $source_phrase_list = explode(PHP_EOL, trim($this->source_phrase));

            if(sizeof($source_phrase_list)>Yii::$app->user->identity->suggest_limit_words){
                $this->addError('source_phrase','Превышен лимит на количество исходных ключевых фраз, вам разрешено '.Yii::$app->user->identity->suggest_limit_words.' ключевых фраз');
            }

            //по каждому ключевому слову выполняем валидацию
            foreach($source_phrase_list as $source_phrase_word)
            {

                $source_phrase_word = trim($source_phrase_word);

                if(empty($source_phrase_word))
                {
                    $this->addError('source_phrase','В списке исходных ключевых фраз есть пустая строка');
                }

                //при появлении ошибки - остановим дальнейшие проверки
                if($this->hasErrors()){ break; }

                if(strlen($source_phrase_word)>255)
                {
                    $this->addError('source_phrase','Длина фразы не может быть длинее 255 символов');
                }


                //фраза без учета звездочки и пробелов не может быть короче 3 символов
                //$source_phrase = str_replace([' '], '',$source_phrase_word);
                if(strlen($source_phrase_word)<3)
                {
                    $this->addError('source_phrase',"Длина фразы '$source_phrase_word' не может быть короче 3х символов");
                }

                //фраза не должна соответствовать ни одному из минус-слов
                if(!$this->hasErrors())
                {

                    //получаем массив минус-слов
                    $this->explodedStopWords();

                    if($this->stop_words_exploded)
                    {
                        //поиск фразы в массиве минус-слов
                        if(in_array($source_phrase_word, $this->stop_words_exploded))
                        {
                            $this->addError('source_phrase'," Указанная вами фраза '$source_phrase_word' встречается в минус словах");
                        }
                    }
                }

                //Ключевые фразы и минус-слова нужно еще валидировать на такуювещь, как наличие знака = только в начале строки.
                if(!$this->hasErrors())
                {
                    //разбиваем запрос на слова
                    $words = explode(' ',$source_phrase_word);

                    foreach($words as $word){

                        $word  = trim($word);

                        //при появлении первой ошибки - остановка цикла
                        if($this->hasErrors()){ break;}

                        if(preg_match('/=/imu', $word) && !preg_match('/^[=]*[a-z0-9а-яїіє]+$/imu', $word))
                        {
                            $this->addError('source_phrase', "В исходной фразе '$source_phrase_word' знак «равно» может находиться только в начале слова.");

                        }
                    }

                }

                //регулярка-^[a-z0-9а-яїіє ]+$
                if(!$this->hasErrors())
                {
                    if(!preg_match('/^[a-z0-9а-яїіє= ]+$/imu', $source_phrase_word))
                    {
                        $this->addError('source_phrase', "Исходная фраза '$source_phrase_word' введена в неверном формате");
                    }
                }

                //формируем hash-сумму по параметрам выборки и проверяем на дубли
                if(!$this->hasErrors())
                {
                    //формируем HASH, на основании общих параметров выборки
                    $hash = $this->createHash($source_phrase_word);

                    //поиск совпадения в списке хешей
                    if(in_array($hash, $this->hash_list))
                    {
                        $this->addError('source_phrase',"Исходная фраза '$source_phrase_word' продублирована у вас в списке исходных ключевых фраз");
                    }

                    //если ранее была создана выборка с такими же параметрами то сверим с бд
                    if(!$this->hasErrors())
                    {
                        $db_hash = Yii::$app->db
                            ->createCommand("SELECT id FROM selections WHERE user_id=:user_id AND hash=:hash")
                            ->bindValues([":hash"=>$hash, ":user_id"=>Yii::$app->user->id])
                            ->queryScalar();

                        //нашли совпадение, юзер ранее добавлял такую выборку, нашли в БД
                        if($db_hash)
                        {
                            $this->addError('source_phrase',"Выборка по исходной фразе '$source_phrase_word' с такими же параметрами уже была создана вам ранее");
                        }

                    }

                    if(!$this->hasErrors()) { $this->hash_list[] = $hash; }
                }


                // добавим в общий список ключевых слов - провалидированное ключ. слово
                if(!$this->hasErrors())
                {
                    $this->source_phrase_list[] = $source_phrase_word;
                }
            }
        }
    }

    /*
     * преобразовываем список минус-слов из текста в массив
     */
    public function explodedStopWords()
    {
        if(!$this->hasErrors() && !empty($this->stop_words) && empty($this->stop_words_exploded))
        {
            $stop_words_exploded = explode(PHP_EOL, $this->stop_words);

            foreach($stop_words_exploded as $stop_word){

                $stop_word = trim($stop_word);

                if(!empty($stop_word)){
                    $this->stop_words_exploded[] = trim($stop_word);
                }

            }
        }
    }

    /*
     * если заполнен список минус-слов - проверим этот параметр
     */
    public function validateStopWords()
    {
        if(!$this->hasErrors() && !empty($this->stop_words))
        {
            $stop_words_list = explode(PHP_EOL, trim($this->stop_words));

            if(sizeof($stop_words_list)>Yii::$app->user->identity->suggest_limit_stop_words)
            {
                $this->addError('stop_words',"Разрешено не более ".Yii::$app->user->identity->suggest_limit_stop_words." минус-слов");
            }

            //проверим каждое минус-слово отдельно
            if(!$this->hasErrors())
            {
                foreach($stop_words_list as $word)
                {
                    $word = trim($word);

                    //проверка длины минус-слова
                    $minus_word_len = strlen(str_replace('=','',$word));

                    //менее 3х слов - ошибка
                    if($minus_word_len<3){$this->addError('stop_words',"В минус-слове '$word' длина фразы не может быть менее 3х символов");}

                    if(!empty($word))
                    {

                        //Ключевые фразы и минус-слова нужно еще валидировать на такуювещь, как наличие знака = только в начале строки.
                        if(!$this->hasErrors())
                        {
                            //разбиваем запрос на слова
                            $minus_words = explode(' ',$word);

                            foreach($minus_words as $minus_word){

                                $minus_word  = trim($minus_word);

                                //при появлении первой ошибки - остановка цикла
                                if($this->hasErrors()){ break;}

                                if(preg_match('/=/imu', $minus_word) && !preg_match('/^[=]*[a-z0-9а-яїіє]+$/imu', $minus_word))
                                {
                                    $this->addError('stop_words', "В минус слове '$word' знак «равно» может находиться только в начале слова.");

                                }
                            }
                        }

                        //не обнаружили ошибок ранее - далее проверяем
                        if(!$this->hasErrors()){
                            if(!preg_match('/^[a-z0-9а-яїіє= ]+$/imu',$word))
                            {
                                $this->addError('stop_words',"Минус-слово: '$word' имеет недопустимый формат");
                                break;
                            }else{
                                $this->stop_words_exploded[] = trim($word);
                            }
                        }
                    }

                }
            }
        }
    }


    /*
     * валидируем числовые параметры
     * параметр "От" не может быть больше параметра "До"
     */
    public function validateParamsFromTo()
    {

        if(!$this->hasErrors())
        {
            foreach($this->attributes() as $attribute)
            {
                //если есть параметр с "from" то должен быть и параметр с таким же именем но с окончанием "to"
                if(preg_match('/from$/',$attribute))
                {
                    $param = str_replace('_from','', $attribute);

                    $from = $this->$attribute;//параметр "От"

                    if(isset($this->{$param.'_to'}))
                    {
                        //параметр "От" не должен быть больше параметра "До"
                        if($this->$attribute > $this->{$param.'_to'})
                        {
                            $this->addError($attribute,
                                'Значение поля "'.$this->getAttributeLabel($attribute).'" не может быть больше значения поля "'.
                                $this->getAttributeLabel($param.'_to').'"');
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id','source_phrase',  'potential_traffic', 'source_words_count_from', 'source_words_count_to',
                'position_from', 'position_to', 'suggest_words_count_from', 'suggest_words_count_to',
                'length_from', 'length_to', 'need_wordstat',  'wordstat_from', 'wordstat_to'], 'required'],

            ['base_id', 'default', 'value'=>Yii::$app->params['subscribe_suggest_and_wordstat']],

            [['potential_traffic', 'wordstat_syntax', 'base_id'], 'integer'],

            //индивидуальные лимиты по цифровым параметрам
            [['wordstat_from', 'wordstat_to'], 'integer', 'min'=>1, 'max'=>100000000],
            [['position_from', 'position_to'], 'integer', 'min'=>1, 'max'=>10],
            ['need_wordstat', 'integer', 'min'=>0, 'max'=>1],
            [['length_from', 'length_to'], 'integer', 'min'=>1, 'max'=>256],
            [['source_words_count_from', 'source_words_count_to','suggest_words_count_from', 'suggest_words_count_to'], 'integer','min'=>1, 'max'=>32],

            //проверка всех текстовых полей, в которых указывается интервал чисел (От и До)
            ['wordstat_from','validateParamsFromTo'],

            //проверка списка минус-слов
            ['stop_words', 'validateStopWords'],

            //валидируем список ключевых слов(важна последовательность валидации,сперва валидируем минус-слова, а потом уже ключевики)
            ['source_phrase','validateSourcePhrase'],
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
            'source_phrase_list'=>'Список ключевых фраз',
            'source_phrase' => 'Исходная ключевая фраза',
            'results_count' => 'кол-во результатов',
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
            'stop_words_limit'=>'Лимит на список минус-слов',
            'source_phrase_list_limit'=>'Лимит на добавление исходных ключевых фраз',

        ];
    }

    /*
     * Создание выборок, на основе провалидированных данных из формы
     */
    public function createSelects()
    {
        //обработаем список ключевых слов для создания выборок
        if($this->source_phrase_list)
        {
            //цикл по списку ключевых слов
            foreach($this->source_phrase_list as $source_phrase)
            {
                //обрабатываем список ключевых слов и каждый сохраняем отдельно
                $model = new Selections();

                //заполняем значениями из модели-проверки - поля задания на выборку
                foreach($this->attributes() as $attribute)
                {
                    if($model->hasAttribute($attribute))
                    {
                        $model->setAttribute($attribute, $this->$attribute);
                    }
                }

                $model->name = str_replace('=','',trim($source_phrase));

                $model->hash = $this->createHash($source_phrase);

                $model->source_phrase = trim($source_phrase);

                //денормализация данных
                if($this->stop_words_exploded){
                    $model->minus_words = json_encode($this->stop_words_exploded);
                }

                //если параметры указаны верно, сохраним задание на выборку
                if($model->validate())
                {

                    if($model->save())
                    {
                        if($this->stop_words_exploded)
                        {
                            //запишим подвязанные к выборке список минус-слов(стоп-слов)
                            foreach($this->stop_words_exploded as $stop_word_relation)
                            {
                                Yii::$app->db->createCommand()->insert('minus_words',['selection_id'=>$model->id,'minus_word'=>$stop_word_relation])->execute();
                            }
                        }
                    }
                }else{
                    echo '<pre>'; print_r($model->errors); die();
                }
            }
        }

    }

    /*
     * формируем хеш-сумму по указанным параметрам выборки, для исключения дублирующихся выборок у пользователя
     * HASH - сумма формируется для каждого отдельного слова по выборке
     * $source_phrase - ключевое слово, по которому будет отдельная выборка
     */
    public function createHash($source_phrase)
    {
        $hash = trim($source_phrase).$this->potential_traffic.$this->source_words_count_from.$this->source_words_count_to.$this->position_from.$this->position_to.$this->suggest_words_count_from;

        $hash.=$this->suggest_words_count_to.$this->length_from.$this->length_to.$this->need_wordstat.$this->wordstat_syntax.$this->wordstat_from.$this->wordstat_to;

        //если указан список минус-слов, используем его
        if($this->stop_words_exploded){
            $hash.=$this->type.'w'.implode('',$this->stop_words_exploded);
        }

        return md5($hash);
    }

}