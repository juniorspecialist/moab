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

class SelectionsSearch extends Selections{

    public $search;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search'], function ($attribute) {
                $this->$attribute = \yii\helpers\HtmlPurifier::process($this->$attribute);
            }],
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

        $query->joinWith(['category']);

        //если пользователь НЕ админ, показываем только его финансы
        //if user not admin
        if( \Yii::$app->user->identity->isAdmin()){
            //$query->joinWith(['author','authorLastMsg']);
        }else{
            $query->andFilterWhere([
                self::tableName().'.user_id' => Yii::$app->user->id,
            ]);
        }

        $query->orderBy('date_created DESC');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*
        if( \Yii::$app->user->identity->isAdmin()){

            $query->andFilterWhere([
                'tbl_tickets.id' => $this->id,
                'tbl_tickets.prioritet' => $this->prioritet,
                'tbl_tickets.status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'user.email', $this->author_id]);

            $query->andFilterWhere(['like', 'u2.email', $this->author_id_last_msg]);

            $query->andFilterWhere(['like', 'tbl_tickets.theme', $this->theme]);
        }*/

        return $dataProvider;
    }
}