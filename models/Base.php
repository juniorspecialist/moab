<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "base".
 *
 * @property integer $id
 * @property string $title
 *
 * @property UserSubscription[] $userSubscriptions
 */
class Base extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'base';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['hide_bases', 'string'],
            [['title', 'one_month_price','three_month_price','six_month_price','twelfth_month_price','eternal_period_price'], 'required'],
            [['one_month_price','three_month_price','six_month_price','twelfth_month_price','eternal_period_price','enabled_user'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['one_month_user_info','three_month_user_info','six_month_user_info','twelfth_month_user_info','eternal_period_user_info','cabinet_link'], 'string', 'max' => 128],

            [['hide_bases', 'hidebases','last_update','next_update','count_keywords','add_in_update'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'one_month_price'=>'Цена(1 месяц)',
            'three_month_price'=>'Цена(3 месяца)',
            'six_month_price'=>'Цена(6 месяцев)',
            'twelfth_month_price'=>'Цена(12 месяцев)',

            'one_month_user_info'=>'Пользовательская информация(1 месяц)',
            'three_month_user_info'=>'Пользовательская информация(3 месяца)',
            'six_month_user_info'=>'Пользовательская информация(6 месяцев)',
            'twelfth_month_user_info'=>'Пользовательская информация(12 месяцев)',
            'eternal_period_price'=>'Цена(вечной лицензии)',
            'eternal_period_user_info'=>'Пользовательская информация(вечная)',
            'enabled_user'=>'Доступна пользователям',

            'hidebases'=>'Какие базы скрываем при отображении текущей',
            'last_update'=>'Последнее обновление',
            'next_update'=>'Следующее обновление',
            'count_keywords'=>'Всего ключевых фраз',
            'add_in_update'=>'Добавлено в обновлении',
            'cabinet_link'=>'Ссылка на работу с базой внутри кабинет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSubscription()
    {
        return $this->hasMany(UserSubscription::className(), ['base_id' => 'id']);
    }

    //денормолизация данных, чтобы не плодить таблиц, данные не используются для фильтрации
    public function getHidebases()
    {
        if(empty($this->hide_bases)){
            return [];
        }else{
            return json_decode($this->hide_bases);
        }
    }

    public function setHidebases($value)
    {
        $this->hide_bases = json_encode($value);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }

    static function getPriceOfBase($base, $attribute)
    {
        if(isset($base->{$attribute}))
        {
            return $base->{$attribute};
        }
    }

    public function afterFind()
    {

        parent::afterFind();
    }

    /*
     * формируем ссылку для кнопки в списке подписок юзера
     * логика - если это веб-версия бд, то ссылка на страницу внутри кабинета, где юзер создаёт выборке по бд
     * если это не веб-версия бд, то юзер переходит на страницу доступов RDP
     */
    public function getUrlInfoBase(){
        if(empty($this->cabinet_link)){
            return Url::to(['/info']);
        }
        return Url::to($this->cabinet_link);
    }
    /*
     * по ID базы находим её заголовок
     */
    static function getTitleBase($id){
        if($id){
            return Yii::$app->db->createCommand('SELECT title FROM base WHERE id=:id')->bindValues([':id'=>$id])->queryScalar();
        }
        return '';
    }
}
