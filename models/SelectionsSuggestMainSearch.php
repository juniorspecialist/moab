<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.11.15
 * Time: 19:43
 */

namespace app\models;

use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;

class SelectionsSuggestMainSearch extends SelectionsSuggestSearch{
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

        //фильтруем помеченные на удаление выборки
        $query->andFilterWhere([
            Selections::tableName().'.is_del' => 0,
        ]);

        //используем обязательно фильтр по ID-base(в данном случае по SUGGEST базе)
        $query->andFilterWhere([
            self::tableName().'.base_id' => Yii::$app->params['subsribe_moab_suggest'],
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