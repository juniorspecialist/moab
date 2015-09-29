<?php

namespace app\models;

use Yii;
use app\modules\user\models\User;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "tbl_tickets".
 *
 * @property integer $id
 * @property integer $created
 * @property integer $date_last_msg
 * @property string $theme
 * @property integer $prioritet
 * @property integer $author_id
 * @property integer $author_id_last_msg
 * @property integer $status
 * @property integer $is_new_for_user
 *
 * @property User $author
 * @property TblTicketsEvents[] $tblTicketsEvents
 */
class Tickets extends \yii\db\ActiveRecord
{

    const PRIORITET_LOW = 1;// низкий приоритет
    const PRIORITET_MIDDLE = 2;// средний приоритет
    const PRIORITET_HIGH = 3;// высокий приоритет

    const STATUS_OPEN = 1;// статус - открыт
    const STATUS_CLOSE = 2;// статус - закрыт

    public $question;//вопрос - текст тикета
    public $msg;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tickets';
    }

    public function onlyoneticketrule(){

        if($this->isNewRecord){
            if(!$this->hasErrors()){
                $count = Tickets::find()->where(['status'=>Tickets::STATUS_OPEN, 'author_id'=>Yii::$app->user->id])->count() ;
                if($count>0){
                    $this->addError('theme', 'Нельзя создать новый тикет, пока у вас есть открытые тикеты');
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
            [['theme', 'prioritet', 'question'], 'required'],
            [['created', 'date_last_msg', 'prioritet', 'author_id', 'author_id_last_msg', 'status', 'is_new_for_user','is_new_for_admin'], 'integer'],
            [['theme'], 'string', 'max' => 255],
            [['question'], 'string', 'max' => 5255],


            ['theme', 'onlyoneticketrule', 'message'=>'Только один может быть открытый тикет'],

            [['msg','question', 'theme'], 'filter','filter'=>function($data){
                return HtmlPurifier::process($data);
            }],

            //dafault values
            ['author_id', 'default', 'value'=>Yii::$app->user->id],
            ['is_new_for_admin', 'default', 'value'=>1],
            [['created','date_last_msg'], 'default', 'value'=>time()],
            ['status', 'default', 'value'=>self::STATUS_OPEN],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер тикета',
            'created' => 'Дата создания ',
            'date_last_msg' => 'Дата последнего сообщения',
            'theme' => 'Тема',
            'prioritet' => 'Приоритет',
            'author_id' => 'Автор тикета',
            'author_id_last_msg' => 'Автор последнего сообщения',
            'status' => 'Статус тикета – закрыт или открыт',
            'is_new_for_user' => 'Есть ли не просмотренные сообщения для юзера(показываем конвертик в меню)',
            'question'=>'Текст вопроса',
        ];
    }

    /*
     * получаем список приортетов для тикета
     */
    public static function getPrioritetList(){
        return [
            Tickets::PRIORITET_LOW=>'Низкий',
            Tickets::PRIORITET_MIDDLE=>'Средний',
            Tickets::PRIORITET_HIGH=>'Высокий',
        ];
    }

    /*
     * получаем текстовое описание приоритета тикета
    */
    public function getPrioritetColor()
    {
        $class = '';

        $list = self::getPrioritetList();

        //<span class="label label-primary">

        if($this->prioritet==Tickets::PRIORITET_LOW){ $class = 'label label-success'; }
        if($this->prioritet==Tickets::PRIORITET_MIDDLE){ $class = 'label label-warning'; }
        if($this->prioritet==Tickets::PRIORITET_HIGH){ $class = 'label label-danger'; }

        return Html::tag('span',$list[$this->prioritet],  ['class'=>$class]);
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_OPEN=>'Открыт',
            self::STATUS_CLOSE=>'Закрыт',
        ];
    }

    public function getStatusName()
    {
        $list = self::getStatusList();

        return $list[$this->status];
    }

    /*
     * текстовое описание статус
     */
    public function getTextstatus()
    {

        $status_list = $this->getStatusList();

        return $status_list[$this->status];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorLastMsg()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id_last_msg'])->from(User::tableName() . ' u2');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(TicketsEvents::className(), ['ticket_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        //закрытие тикета
        if($this->status==Tickets::STATUS_CLOSE)
        {
            $event = new TicketsEvents();
            $event->ticket_id = $this->id;
            $event->msg = 'Закрыл тикет';
            $event->event_status = TicketsEvents::STATUS_CLOSE;
            $event->created = time();
            $event->save();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public static function countOpen(){
        return Tickets::find()->where(['status'=>Tickets::STATUS_OPEN])->count();
    }

    /*
     * в зависимости от роли юзера считаем кол-во не просмотренных тикетов с новыми сообщениями
     */
    public static function countIsNew(){
        //для админа
        if(Yii::$app->user->identity->isAdmin())
        {
            return Tickets::find()->where(['is_new_for_admin'=>1])->count();
        }else{
            return Tickets::find()->where(['is_new_for_user'=>1])->count();
        }
    }

    /*
        * получаем кусок темы нужной длины
        * по умолчанию не более 100символов
        */
    public function themeByLength($trimLength = 100){
        $length = strlen($this->theme);
        if ($length > $trimLength) {
            $count = 0;
            $prevCount = 0;
            $array = explode(" ", $this->theme);
            foreach ($array as $word) {
                $count = $count + strlen($word);
                $count = $count + 1;
                if ($count > ($trimLength - 3)) {
                    return substr($this->theme, 0, $prevCount) . "...";
                }
                $prevCount = $count;
            }
        } else {
            return $this->theme;
        }
    }
}
