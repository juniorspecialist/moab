<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.10.15
 * Time: 10:07
 */

namespace app\models;

use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;

class SelectionsSuggestSearch extends Selections{

    public $search;
    public $ids;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search'], function ($attribute) {
                $this->$attribute = \yii\helpers\HtmlPurifier::process($this->$attribute);
            }],
            ['ids', function($attribute){
                if(!is_array($this->$attribute)){
                    $this->addError($attribute, 'Список параметров должен быть массивом');
                }
            }],
            // checks if every ID is an integer
            ['ids', 'each', 'rule' => ['integer']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Selections::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        $query->joinWith(['category','base']);

        $query->select('selections.*');

        //если пользователь НЕ админ, показываем только его финансы
        //if user not admin
        if( \Yii::$app->user->identity->isAdmin()){
            //$query->joinWith(['author','authorLastMsg']);
        }else{
            $query->andFilterWhere([
                self::tableName().'.user_id' => Yii::$app->user->id,
            ]);
        }

        //используем обязательно фильтр по ID-base(в данном случае по SUGGEST базе)
        $query->andFilterWhere([
            self::tableName().'.base_id' => Yii::$app->params['subscribe_suggest_and_wordstat'],
        ]);


        $query->orderBy('date_created DESC, id DESC');


        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['in',self::tableName().'.id', $this->ids]);


        //объединили поля и поиск совпадений по разным полям
        $query->andFilterWhere(['like','CONCAT(category.title,selections.name,selections.source_phrase,selections.results_count)', $this->search]);


        return $dataProvider;
    }
}