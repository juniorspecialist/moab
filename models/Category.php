<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 *
 * @property User $user
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required','message'=>'Укажите название группы'],
            [['user_id'], 'integer'],
            ['user_id', 'default', 'value'=>Yii::$app->user->id],
            ['title', 'unique', 'targetAttribute' => ['user_id','title'], 'message'=>'Название группы "{value}" уже добавлено'],

            [['title'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Группа',
            'user_id' => 'Пользователь',
            // a1 needs to be unique, but column a2 will be used to check the uniqueness of the a1 value

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /*
     * перед удалением группы, обновим все поля выборок по этой группе на "Без группы"
     */
    public function beforeDelete(){

        if(parent::beforeDelete()){

            //есть ли у юзера выборки, в которых подвязана категория, которую удаляем
            $change_data = Yii::$app->db->createCommand("SELECT id FROM ".Selections::tableName()." WHERE user_id=:user_id AND category_id=:category_id LIMIT 1")
                            ->bindValues([':user_id'=>Yii::$app->user->id, 'category_id'=>$this->id])
                            ->queryScalar();

            //есть выборки для обновления по ним категории
            if($change_data){

                //поиск ID группы "Без группы"
                $no_name_category = Yii::$app->db
                    ->createCommand('SELECT id FROM category WHERE user_id=:user_id AND title="Без группы"')
                    ->bindValues([':user_id'=>Yii::$app->user->id])
                    ->queryScalar();


                if(empty($no_name_category)){
                    return false;
                }

                Yii::$app->db
                    ->createCommand()
                    ->update(Selections::tableName(), ['category_id'=>$no_name_category],['category_id'=>$this->id, 'user_id'=>Yii::$app->user->id])
                    ->execute();
            }

            return true;

        }else{
            return false;
        }
    }

    /*
     * список категорий по пользователю
     */
    static function getCategoryArrayByUser(){
        return Yii::$app->db->createCommand('SELECT id, title FROM category WHERE user_id=:user_id ORDER BY id DESC')->bindValues([':user_id'=>Yii::$app->user->id])->queryAll();
    }


    /*
     * категория - без группы
     * по пользователю
     */
    static function getWithOutGroup(){
        return Yii::$app->db->createCommand('SELECT id FROM category WHERE user_id=:user_id AND title=:title')->bindValues([':user_id'=>Yii::$app->user->id, ':title'=>'Без группы'])->queryScalar();
    }
}
