<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.04.15
 * Time: 22:08
 */

namespace app\modules\user\models;

use app\components\MainHelper;
use app\models\UserSubscription;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class UserSearch extends User{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','balance'], 'integer'],
            [['status', 'email', ], 'safe'],
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

        //$query = User::find();
        $query = new Query();

        $query->from('user');

        $this->load($params);

        if (!$this->validate()) {
            $dataProvider = new ArrayDataProvider([
                'allModels'=>$query->all(),
                'pagination' => [
                    'pageSize' => 50,
                ]
            ]);

            $users = $query->all();

            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        $users = $query->all();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$users,
            'pagination' => [
                'pageSize' => 50,
            ]
        ]);
        //////////////////////////////////////////////////////////////////////////////////////////
        $ids = ArrayHelper::map($dataProvider->getModels(),'id', 'id');

        //поиск последний дат входа пользователя по списку - ID
        $subscribe = UserSubscription::find()
            ->with('base')
            ->andWhere(['in','user_id',$ids])
            ->asArray()
            ->all();


        $data = [];

        foreach($dataProvider->getModels() as $user) {

            $find = MainHelper::findInArray($subscribe,'user_id', $user['id']);

            if($find){
                $user['_subscribe'][] = $find;//ArrayHelper::getColumn($find, 'base');
            }else{
                $user['_subscribe'] = [];
            }
            //$dataProvider->allModels[] = $user;
            $data[] = $user;
        }

        $dataProvider->setModels($data);

        return $dataProvider;
    }
}