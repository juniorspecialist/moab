<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 24.11.15
 * Time: 23:42
 */

namespace app\models;

use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;

class FinancySearch extends Financy{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['amount', 'type_operation', 'desc','pay_system'], 'required'],
//            [['status', 'user_id', 'balance_after', 'amount', 'type_operation', 'create_at','pay_system'], 'integer'],
//            ['amount', 'integer', 'min'=>1],
//            ['create_at', 'default', 'value'=>time()],
//            ['status', 'default', 'value'=>self::STATUS_NOT_PAID],
//            ['user_id', 'default', 'value'=>\Yii::$app->user->id],
//            ['desc', 'string', 'max' => 600],
            [['amount','desc','pay_system','status','type_operation','status','user_id'],'safe'],
        ];
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
        $query = Financy::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        $query->joinWith(['user']);


        $query->orderBy('create_at DESC, id DESC');


        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'user.email', $this->user_id]);

        $query->andFilterWhere(['like','desc', $this->desc]);

        $query->andFilterWhere(['financy.type_operation'=>$this->type_operation]);

        $query->andFilterWhere(['financy.status'=>$this->status]);



        return $dataProvider;
    }
}