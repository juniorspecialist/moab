<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.09.15
 * Time: 10:58
 */

namespace app\models;


use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;

class TicketsSearch extends Tickets{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','prioritet',  'status'], 'integer'],

            [['theme', 'author_id_last_msg', 'author_id'], function ($attribute) {
                $this->$attribute = \yii\helpers\HtmlPurifier::process($this->$attribute);
            }],


            [['theme','author_id','author_id_last_msg'], 'string'],
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
        $query = Tickets::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        //если пользователь НЕ админ, показываем только его финансы
        //if user not admin
        if( \Yii::$app->user->identity->isAdmin()){
            $query->joinWith(['author','authorLastMsg']);
        }else{
            $query->andFilterWhere([
                'tbl_tickets.user_id' => Yii::$app->user->id,
            ]);
        }

        $query->orderBy('date_last_msg DESC');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if( \Yii::$app->user->identity->isAdmin()){

            $query->andFilterWhere([
                'tbl_tickets.id' => $this->id,
                'tbl_tickets.prioritet' => $this->prioritet,
                'tbl_tickets.status' => $this->status,
            ]);

            $query->andFilterWhere(['like', 'user.email', $this->author_id]);

            $query->andFilterWhere(['like', 'u2.email', $this->author_id_last_msg]);

            $query->andFilterWhere(['like', 'tbl_tickets.theme', $this->theme]);
        }

        return $dataProvider;
    }
}