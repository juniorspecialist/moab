<?php

namespace app\models;

use Yii;
use yii\helpers\HtmlPurifier;
use app\modules\user\models\User;

/**
 * This is the model class for table "tbl_tickets_events".
 *
 * @property integer $id
 * @property integer $ticket_id
 * @property string $msg
 * @property integer $created
 * @property integer $user_id
 * @property integer $event_status
 *
 * @property TblTickets $ticket
 * @property User $user
 */
class TicketsEvents extends \yii\db\ActiveRecord
{

    const STATUS_ADD_MSG = 1;//добавили сообщение
    const STATUS_OPEN = 2; //открыли тикет
    const STATUS_CLOSE = 3; //закрыли тикет

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tickets_events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_id', 'msg', 'event_status'], 'required'],
            [['ticket_id', 'created', 'user_id', 'event_status'], 'integer'],
            [['msg'], 'string'],
            ['msg', 'filter','filter'=>function($data){
                return HtmlPurifier::process($data);
            }],
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
            ['created','default', 'value'=>time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'ID тикета(к которому подвязано событие)',
            'msg' => 'текст сообщения',
            'created' => 'создано событие',
            'user_id' => 'User ID',
            'event_status' => 'Event Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Tickets::className(), ['id' => 'ticket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
